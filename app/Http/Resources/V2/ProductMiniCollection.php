<?php
namespace App\Http\Resources\V2;
use Illuminate\Http\Resources\Json\ResourceCollection;
class ProductMiniCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                $wholesale_product =
                    ($data->wholesale_product == 1) ? true : false;
                return [
                    'id' => $data->id,


                    'name' => $data->getTranslation('name'),
                    'code' => $data->barcode,
                    'thumbnail_image' => uploaded_asset($data->thumbnail_img),
                    'has_discount' => home_base_price($data, false) != home_discounted_base_price($data, false),
                    'discount' => "-" . discount_in_percentage($data) . "%",
                    'stroked_price' => home_base_price($data),
                    'main_price' => home_discounted_base_price($data),
                    'rating' => (float) $data->rating,
                    'sales' => (int) $data->num_of_sale,
                    'is_wholesale' => $wholesale_product,
                    'stock' => $data->productStock->qty,
                    'stock_id' => $data->productStock->id,
                    'slug' => $data->slug,
                    'links' => [
                        'details' => route('products.show', $data->id),
                    ],
                    'shop'=> [
                                        'id' =>  $data->user->shop->id,
                                        'name' =>  $data->user->shop->name,
                                        'logo' =>  uploaded_asset($data->user->shop->logo) ?? "",
                                        'contact' => $data->user->email,
                                        'mobile' =>$data->user->shop->phone

                    ]
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
