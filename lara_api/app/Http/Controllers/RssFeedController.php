<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessRssFeed;
use Illuminate\Http\Request;

class RssFeedController extends Controller
{
    /**
     * Process the RSS feed.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function processRssFeed()
    {
        $url = 'https://lifehacker.com/feed/rss';
        ProcessRssFeed::dispatch($url);

        return response()->json(['message' => 'RSS feed processing started.']);
    }
}
