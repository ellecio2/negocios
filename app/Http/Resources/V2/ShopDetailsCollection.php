<?php

namespace App\Http\Resources\V2;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopDetailsCollection extends JsonResource {
    public function toArray($request) {
        return
            [
                'id' => $this->id,
                'user_id' => intval($this->user_id),
                'name' => $this->name,
                'title' => $this->meta_title,
                'slug' => $this->slug,
                'description' => $this->meta_description,
                'delivery_pickup_latitude' => $this->delivery_pickup_latitude,
                'delivery_pickup_longitude' => $this->delivery_pickup_longitude,
                'logo' => uploaded_asset($this->logo),
                'package_invalid_at' => $this->package_invalid_at ? $this->package_invalid_at->format('d-m-Y') : null,
                'product_upload_limit' => $this->seller_package->product_upload_limit ?? 0,
                'seller_package' => $this->seller_package->name ?? "",
                'seller_package_img' => uploaded_asset($this->seller_package->logo ?? ""),
                'upload_id' => $this->logo,
                'sliders' => get_images_path($this->sliders),
                'sliders_id' => $this->sliders,
                'address' => $this->address,
                'admin_to_pay' => format_price($this->admin_to_pay),
                'phone' => $this->phone,
                'facebook' => $this->facebook,
                'google' => $this->google,
                'twitter' => $this->twitter,
                'instagram' => $this->instagram,
                'youtube' => $this->youtube,
                'cash_on_delivery_status' => $this->cash_on_delivery_status,
                'bank_payment_status' => $this->bank_payment_status,
                'bank_name' => $this->bank_name,
                'bank_acc_name' => $this->bank_acc_name,
                'bank_acc_no' => $this->bank_acc_no,
                'bank_routing_no' => $this->bank_routing_no,
                'rating' => (double)$this->rating,
                'verified' => $this->verification_status == 1,
                'is_submitted_form' => $this->verification_info != null,
                'verified_img' => $this->verification_status == 1 ? static_asset("assets/img/verified.png") : static_asset("assets/img/non_verified.png"),
                'verify_text' => $this->verification_status == 1 ? translate("Verified seller") : translate("Non-Verified seller"),
                'email' => $this->user->email ?? '',
                'products' => $this->user->products ? $this->user->products->count() : 0,
                'year_orders' => $this->user->seller_orders()
                    ->whereYear('created_at', Carbon::now('America/Santo_Domingo')->year)
                    ->count(),
                'year_sales' => format_price($this->user->seller_sales()
                    ->whereYear('created_at', Carbon::now('America/Santo_Domingo')->year)
                    ->sum('price'), true),
                'month_orders' => $this->user->seller_orders()
                    ->whereYear('created_at', Carbon::now('America/Santo_Domingo')->year)
                    ->whereMonth('created_at', Carbon::now('America/Santo_Domingo')->month)
                    ->count(),
                'month_sales' => format_price($this->user->seller_sales()
                    ->whereYear('created_at', Carbon::now('America/Santo_Domingo')->year)
                    ->whereMonth('created_at', Carbon::now('America/Santo_Domingo')->month)
                    ->sum('price'), true)
            ];
    }

    public function with($request) {
        return [
            'success' => true,
            'status' => 200
        ];
    }

    protected function convertPhotos($data) {
        $result = array();
        foreach ($data as $key => $item) {
            array_push($result, uploaded_asset($item));
        }
        return $result;
    }
}
