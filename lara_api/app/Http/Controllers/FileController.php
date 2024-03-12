<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Handle the image upload request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // adjust max file size as needed
        ]);

        // Check if the request contains a file
        if ($request->hasFile('image')) {
            // Get the file from the request
            $image = $request->file('image');

            $filename = $image->getClientOriginalName();
            $image->move(public_path('images'), $filename);
            $imageUrl = url('images/' . $filename);

            // Manually construct the image URL without encoding slashes
            // $imageUrl = rtrim(config('app.url'), '/') . '/images/' . $filename;
            $imageUrl = 'http://192.168.31.122:8003/images/' . $filename;

            return response()->json(['image_path' => $imageUrl]);
        }

        // Return an error response if no file is found in the request
        return response()->json(['error' => 'No image found in the request'], 400);
    }
}
