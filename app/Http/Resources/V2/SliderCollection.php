<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SliderCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'photo' => uploaded_asset($data['image'] ?? $data),
                    'text1' => $data['text1'] ?? '',
                    'text2' => $data['text2'] ?? '',
                    'text3' => $data['text3'] ?? '',
                    'text4' => $data['text4'] ?? ''
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}