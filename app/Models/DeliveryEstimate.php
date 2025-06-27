<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryEstimate extends Model {
    protected $fillable = [
        'name',
        'delivery_info',
        'cart_id',
    ];

    public $timestamps = false;

    public function cart() {
        return $this->belongsTo(Cart::class);
    }
}
