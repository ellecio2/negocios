<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'delivery_pickup_latitude',
        'delivery_pickup_latitude',
        'user_id'
    ];
}
