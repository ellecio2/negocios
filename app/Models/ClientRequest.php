<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRequest extends Model
{
    use HasFactory;

    protected $fillable = ['estado', 'user_id', 'service_id'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
