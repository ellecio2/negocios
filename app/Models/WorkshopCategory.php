<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopCategory extends Model {

    protected $fillable = [ 'name' ];

    public function categories() {
        return $this->belongsToMany(Category::class, 'workshop_categories_has_categories', 'workshop_category_id', 'category_id');
    }

    public function workshops(){
        return $this->belongsToMany(Workshop::class, 'workshop_has_workshop_categories');
    }
}
