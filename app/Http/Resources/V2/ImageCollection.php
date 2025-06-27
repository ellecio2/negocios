<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ImageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($image) {
                return [
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
                                asset('uploads/all/' . $image->file_name),
                ];
            }),
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}