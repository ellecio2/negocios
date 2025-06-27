<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Subcategorie extends Model {
    /*protected $with = ['category_translations'];*/

    public function getTranslation($field = '', $lang = false){
        $lang = $lang == false ? App::getLocale() : $lang;
        $category_translation = $this->category_translations->where('lang', $lang)->first();
        return $category_translation != null ? $category_translation->$field : $this->$field;
    }

    public function category_translations(){
    	return $this->hasMany(CategoryTranslation::class);
    }

    public function coverImage(){
    	return $this->belongsTo(Upload::class, 'cover_image');
    }

    public function products(){
    	return $this->hasMany(Product::class);
    }

    public function classified_products(){
    	return $this->hasMany(CustomerProduct::class);
    }

    public function categories() {
        return $this->hasMany(Subcategorie::class, 'parent_id');
    }

    public function childrenCategories() {
        return $this->hasMany(Subcategorie::class, 'parent_id')
            ->with('categories');
    }

    public function allChildren(){
        $categories = $this->childrenCategories->map(function($child) {
            return ['id' => $child->id, 'name' => $child->name];
        })->all();

        foreach ($this->childrenCategories as $child) {
            $categories = array_merge($categories, $child->allChildren());
        }

        return $categories;
    }

    public function allChildrenNames(){
        $names = $this->childrenCategories->pluck('name')->toArray();

        foreach ($this->childrenCategories as $child) {
            $names = array_merge($names, $child->allChildrenNames());
        }

        return $names;
    }

    public function parentCategory() {
        return $this->belongsTo(Subcategorie::class, 'parent_id');
    }

    public function topLevelCategory() {
        if ($this->parentCategory) {
            return $this->parentCategory->topLevelCategory();
        } else {
            return $this;
        }
    }

    public function attributes() {
        return $this->belongsToMany(Attribute::class);
    }

    public function workshopCategories(){
        return $this->belongsToMany(WorkshopCategory::class, 'workshop_categories_has_categories', 'category_id', 'workshop_category_id');
    }
}
