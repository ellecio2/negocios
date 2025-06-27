<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCompanyZone extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'latitud',
        'longitud',
        'shipping_company_city_id',
    ];

    public function city()
    {
        return $this->belongsTo(ShippingCompanyCity::class, 'shipping_company_city_id');
    }

    public function towns()
    {
        return $this->hasMany(ShippingCompanyTown::class);
    }
}