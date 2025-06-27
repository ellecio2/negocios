<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FlashDealProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->filter(function($data) {
                // Filtrar solo los elementos que tienen producto válido
                return $data->product !== null;
            })->map(function($data) {
                return [
                    'id' => $data->product_id,
                    'name' => $data->product->name,
                    'slug' => $data->product->slug,
                    'image' => uploaded_asset($data->product->thumbnail_img),
                    'price' => home_discounted_base_price($data->product),
                    'stock' => $data->product->current_stock,
                    'links' => [
                        'details' => route('products.show', $data->product_id),
                    ]
                ];
            })->values() // Reindexar el array después del filtro
        ];
    }
}