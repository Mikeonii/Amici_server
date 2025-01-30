<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadProfilePicture(Request $request)
    {
        // Validate the incoming file
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Folder path under the public disk
        $folderPath = 'profile_pictures'; // Store directly under the public directory

        // Store the file
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs($folderPath, $filename, 'public'); // Store in public disk

        // Return the file URL
        return response()->json([
            'message' => 'File uploaded successfully',
            'filePath' => Storage::url($path), // Return the URL of the uploaded file
        ]);
    }
}


