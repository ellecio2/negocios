<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryService extends Model {

    protected $fillable = [
        'delivery_company',
        'py_info',
        'tb_info',
    ];

    public $timestamps = false;

    public function orderDetail() : HasMany {
        return $this->hasMany(OrderDetail::class);
    }
}
