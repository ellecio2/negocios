<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property Carbon $package_invalid_at
 */
class Shop extends Model {

    protected $with = ['user'];

    protected $fillable = [
        'user_id',
        'name',
        'logo',
        'sliders',
        'top_banner',
        'banner_full_width_1',
        'banners_half_width',
        'banner_full_width_2',
        'phone',
        'address',
        'country',
        'state',
        'city',
        'postalCode',
        'rating',
        'num_of_reviews',
        'num_of_sale',
        'seller_package_id',
        'product_upload_limit',
        'package_invalid_at',
        'verification_status',
        'verification_info',
        'cash_on_delivery_status',
        'admin_to_pay',
        'facebook',
        'instagram',
        'google',
        'twitter',
        'youtube',
        'slug',
        'meta_title',
        'meta_description',
        'pick_up_point_id',
        'shipping_cost',
        'delivery_pickup_latitude',
        'delivery_pickup_longitude',
        'bank_name',
        'bank_acc_name',
        'bank_acc_no',
        'bank_payment_status',
        'rnc',
        'rnc_id'
    ];

    protected $casts = [
        'package_invalid_at' => 'datetime'
    ];

    protected $attributes = [
        'seller_package_id' => 4,
        'verification_status' => 1,
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function seller_package() {
        return $this->belongsTo(SellerPackage::class);
    }

    public function followers() {
        return $this->hasMany(FollowSeller::class);
    }

    public function logo(){
        return Upload::find($this->logo)->file_name;
    }

    public function rncPhoto(){
        return Upload::find($this->rnc_id)->file_name;
    }
}
