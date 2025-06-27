<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Http\Resources\V2\ImageCollection;

class ImageController extends Controller
{
    /**
     * Display a listing of the images.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::all();
        return new ImageCollection($images);
    }

    /**
     * Display the specified image.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $image = Image::findOrFail($id);
        
        return response()->json([
            'id' => $image->id,
            'file_original_name' => $image->file_original_name,
            'file_name' => $image->file_name,
            'user_id' => $image->user_id,
            'file_size' => $image->file_size,
            'extension' => $image->extension,
            'type' => $image->type,
            'external_link' => $image->external_link,
            'file_url' => $image->external_link ? 
                        $image->external_link : 
                        asset('/public/' . $image->file_name),
        ]);
    }
}