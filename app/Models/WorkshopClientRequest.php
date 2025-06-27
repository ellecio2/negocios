<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopClientRequest extends Model
{
    use HasFactory;

    protected $table = 'workshop_client_requests'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'estado_solicitud',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
