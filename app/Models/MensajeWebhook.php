<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MensajeWebhook extends Model
{
    use HasFactory;

    protected $table = 'test_mensaje_webhooks';

    protected $fillable = [
        'mensaje'
    ];
}