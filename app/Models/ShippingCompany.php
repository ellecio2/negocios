<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_endpoint',
        'whatsapp_number',
        'default_message',
        'latitude',
        'longitude',
    ];
    public function cities()
    {
        return $this->hasMany(ShippingCompanyCity::class);
    }
}
