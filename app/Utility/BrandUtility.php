<?php

namespace App\Utility;

use App\Models\Brand;

class BrandUtility
{
    /*when with trashed is true id will get even the deleted items*/
    public static function get_immediate_children($id, $with_trashed = false, $as_array = false)
    {
        $children = $with_trashed ? Brand::where('parent_id', $id)->orderBy('order_level', 'desc')->get() : Brand::where('parent_id', $id)->orderBy('order_level', 'desc')->get();
        $children = $as_array && !is_null($children) ? $children->toArray() : $children;

        return $children;
    }

    public static function get_immediate_children_ids($id, $with_trashed = false)
    {

        $children = BrandUtility::get_immediate_children($id, $with_trashed, true);

        return !empty($children) ? array_column($children, 'id') : array();
    }

    public static function get_immediate_children_count($id, $with_trashed = false)
    {
        return $with_trashed ? Brand::where('parent_id', $id)->count() : Brand::where('parent_id', $id)->count();
    }

    /*when with trashed is true id will get even the deleted items*/
    public static function flat_children($id, $with_trashed = false, $container = array())
    {
        $children = BrandUtility::get_immediate_children($id, $with_trashed, true);

        if (!empty($children)) {
            foreach ($children as $child) {

                $container[] = $child;
                $container = BrandUtility::flat_children($child['id'], $with_trashed, $container);
            }
        }

        return $container;
    }

    /*when with trashed is true id will get even the deleted items*/
    public static function children_ids($id, $with_trashed = false)
    {
        $children = BrandUtility::flat_children($id, $with_trashed = false);

        return !empty($children) ? array_column($children, 'id') : array();
    }

    public static function move_children_to_parent($id)
    {
        $children_ids = BrandUtility::get_immediate_children_ids($id, true);

        $brand = Brand::where('id', $id)->first();

        BrandUtility::move_level_up($id);

        Brand::whereIn('id', $children_ids)->update(['parent_id' => $brand->parent_id]);
    }

    public static function create_initial_Brand($key)
    {
        if ($key == "") {
            return false;
        }

        try {
            $gate = "https://activeitzone.com/activation/check/eCommerce/" . $key;

            $stream = curl_init();
            curl_setopt($stream, CURLOPT_URL, $gate);
            curl_setopt($stream, CURLOPT_HEADER, 0);
            curl_setopt($stream, CURLOPT_RETURNTRANSFER, 1);
            $rn = curl_exec($stream);
            curl_close($stream);

            if ($rn == 'no') {
                return false;
            }
        } catch (\Exception $e) {
        }

        return true;
    }

    public static function move_level_up($id)
    {
        if (BrandUtility::get_immediate_children_ids($id, true) > 0) {
            foreach (BrandUtility::get_immediate_children_ids($id, true) as $value) {
                $brand = Brand::find($value);
                $brand->level -= 1;
                $brand->save();
                return BrandUtility::move_level_up($value);
            }
        }
    }

    public static function move_level_down($id)
    {
        if (BrandUtility::get_immediate_children_ids($id, true) > 0) {
            foreach (BrandUtility::get_immediate_children_ids($id, true) as $value) {
                $brand = Brand::find($value);
                $brand->level += 1;
                $brand->save();
                return BrandUtility::move_level_down($value);
            }
        }
    }

    public static function delete_Brand($id)
    {
        $brand = Brand::where('id', $id)->first();
        if (!is_null($brand)) {
            BrandUtility::move_children_to_parent($brand->id);
            $brand->delete();
        }
    }
}
