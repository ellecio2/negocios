<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Workshop extends Model{

    protected $fillable = [
        'name',
        'delivery_pickup_latitude',
        'delivery_pickup_longitude',
        'user_id',
        'is_available'
    ];

    protected $casts = [
        'delivery_pickup_latitude' => 'float',
        'delivery_pickup_longitude' => 'float'
    ];

    public function user() : BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function categories(){
        return $this->belongsToMany(WorkshopCategory::class, 'workshop_has_workshop_categories');
    }
}
