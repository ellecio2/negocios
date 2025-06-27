<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCompanyTown extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'latitud',
        'longitud',
        'dias_disponibles', // Guardado como JSON
        'shipping_company_zone_id',
    ];

    protected $casts = [
        'dias_disponibles' => 'array',
    ];

    public function zone()
    {
        return $this->belongsTo(ShippingCompanyZone::class, 'shipping_company_zone_id');
    }
}