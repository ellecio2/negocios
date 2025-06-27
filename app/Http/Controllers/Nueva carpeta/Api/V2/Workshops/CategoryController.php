<?php

namespace App\Http\Controllers\Api\V2\Workshops;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CategoryController as CategoryWebController;

class CategoryController extends Controller {
    public function index() {
        $categories = collect();
        $workshop = auth()->user()->workshop;
        $workshop_categories = $workshop->categories()->with('categories')->get();

        $workshop_categories->each(function ($workshop_category) use (&$categories) {
            $workshop_category->categories()->each(function ($category) use (&$categories) {
                $categories->push([
                    'id' => $category->id,
                    'name' => $category->name
                ]);
            });
        });

        $categories = $categories->unique('id');

        $availableCategories = CategoryWebController::getCategoryWithChildrens($categories->pluck('name')->toArray(), true);

        return response()->json([
            'result' => 'true',
            'message' => 'success',
            'data' => $availableCategories
        ]);
    }
}
