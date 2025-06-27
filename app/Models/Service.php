<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model {

    protected $fillable = ['product_id', 'user_id', 'order_id'];

    public function clientRequests()
    {
        return $this->hasMany(ClientRequest::class);
    }
}
