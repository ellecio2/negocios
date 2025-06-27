<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandDetail extends Model
{
    use HasFactory;

    public function model()
    {
        return $this->belongsTo(Brand::class);
    }
}
