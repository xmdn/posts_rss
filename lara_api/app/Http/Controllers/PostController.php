<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\FileController;
use Ramsey\Uuid\Uuid;
use DateTime;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private $fileController;

    public function __construct(FileController $fileController)
    {
        $this->fileController = $fileController;
    }

    public function index(Request $request)
    {
        // Get query parameters
        $perPage = $request->input('per_page', 12); // Number of items per page
        $category = $request->input('category'); // Filter by category
        $startDate = $request->input('start_date'); // Start date for date range filter
        $endDate = $request->input('end_date'); // End date for date range filter
        $searchTerm = $request->input('search'); // Search term
        $order = $request->input('order', 'newest'); // Order by newest or oldest (default is newest)
        $page = $request->input('page', 1); // Current page number

        // Start with all posts
        $query = Post::query();

        // Apply category filter if provided
        if ($category && $category !== 'no') {
            $query->where('category', $category);
        }

        // Apply date range filter if both start and end dates are provided
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Apply search filter if search term is provided
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }

        // Order the posts based on the order parameter
        if ($order === 'oldest') {
            $query->orderBy('updated_at', 'asc');
        } else {
            $query->orderBy('updated_at', 'desc'); // Default to newest
        }

        // Paginate the results
        $posts = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($posts);
    }
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        return response()->json($post);
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload the image using the FileController
        $fileController = new FileController();
        $imageUrlResponse = $fileController->upload($request);
        $imageUrlData = json_decode($imageUrlResponse->getContent(), true);
        $imageUrl = $imageUrlData['image_path'];

        // Generate a new GUID
        $guid = Uuid::uuid4();

        // Ensure that the user is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Retrieve the authenticated user's name
        $userName = auth()->user()->name;

        // Convert the GUID to string
        $guidString = $guid->toString();

        // Set the timezone to your desired timezone
        date_default_timezone_set('Europe/London');

        // Get the current date and time
        $currentDateTime = new DateTime();

        // Format the current date and time according to the RFC 2822 format (required for pubDate)
        $pubDate = $currentDateTime->format(DATE_RFC2822);

        // Create a new post
        $post = new Post();
        $post->title = $request->input('title');
        $post->guid = $guidString;
        $post->link = '';
        $post->author = $userName;
        $post->pubDate = $pubDate;
        $post->category = $request->input('category');
        $post->description = $request->input('description');
        $post->content = $request->input('content');
        $post->thumbnail = $imageUrl; // Store the image URL
        $post->save();

        return response()->json(['message' => 'Post created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        // Find the post by ID
        $post = Post::find($id);

        // Generate a new GUID
        $guid = Uuid::uuid4();

        // Convert the GUID to string
        $guidString = $guid->toString();

        // If the post doesn't exist, return a 404 response
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        // return $request;

        $userName = auth()->user()->name;

        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Allow updating image
        ]);
        

        // Upload the image if provided
        if ($request->hasFile('image')) {
            $fileController = new FileController();
            $imageUrlResponse = $fileController->upload($request);
            $imageUrlData = json_decode($imageUrlResponse->getContent(), true);
            $post->thumbnail = $imageUrlData['image_path'];
        }
        

        // Update the post data
        $post->guid = $guidString;
        $post->link = '';
        $post->author = $userName;
        $post->title = $request->input('title');
        $post->category = $request->input('category');
        $post->description = $request->input('description');
        $post->content = $request->input('content');
        $post->save();

        return response()->json(['message' => 'Post updated successfully'], 200);
    }
}
