<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCompanyCity extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'precio',
        'shipping_company_id',
    ];

    public function shippingCompany()
    {
        return $this->belongsTo(ShippingCompany::class);
    }

    public function zones()
    {
        return $this->hasMany(ShippingCompanyZone::class);
    }
}