<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'shipping_company_id',
        'cart_id',
        'user_id',
        'request_payload',
        'response_payload',
        'status',
        'shipping_reference'
    ];
    
    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
    ];
    
    public function shippingCompany()
    {
        return $this->belongsTo(ShippingCompany::class);
    }
    
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}