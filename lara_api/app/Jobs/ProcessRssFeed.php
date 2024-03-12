<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use App\Models\Post;

class ProcessRssFeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Fetch the RSS feed
        $response = Http::get($this->url);

        if ($response->successful()) {

            // Parse the RSS feed
            $xml = new SimpleXMLElement($response->body());

            $items = [];
            // Process each item in the RSS feed
            foreach ($xml->channel->item as $item) {
                $thumbnail = isset($item->children('media', true)->thumbnail) ? (string) $item->children('media', true)->thumbnail->attributes()['url'] : '';
                $post = Post::firstOrNew(['title' => (string) $item->title]);

                $items[] = [
                    'title' => (string)$item->title,
                    // 'link' => (string)$item->link,
                    // 'guid' => (string)$item->guid,
                    // 'description' => (string)$item->description,
                    // 'pubDate' => (string)$item->pubDate,
                    // 'author' => (string)$item->author,
                    // 'thumbnail' => $thumbnail,
                    // 'content' => (string)$item->children('content', true)->encoded,
                    // 'category' => (string)$item->category,
                    // Add more fields as needed
                ];
                if (!$post->exists) {
                    $post->guid = (string) $item->guid;
                    $post->link = (string) $item->link;
                    $post->description = (string) $item->description;
                    $post->pubDate = (string) $item->pubDate;
                    $post->author = (string) $item->author;
                    $post->thumbnail = $thumbnail;
                    $post->content = (string) $item->children('content', true)->encoded;
                    $post->category = (string) $item->category;
                    $post->save();
                }
            }

            // Output the fetched RSS feed items to the console
            // print_r('Parced!');

        }
    }

}
