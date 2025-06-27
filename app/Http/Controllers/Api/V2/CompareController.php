<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\CompareItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\V2\ProductMiniCollection;
use App\Http\Resources\V2\ProductDetailCollection;
use Illuminate\Support\Facades\Auth;

class CompareController extends Controller
{
    /**
     * Get either authenticated user ID or device ID from request
     */
    private function getIdentifier(Request $request)
    {
        if (Auth::check()) {
            return ['user_id' => Auth::id()];
        }
        
        // If no user is logged in, use device_id from request
        if (!$request->has('device_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Device ID is required for guest users'
            ], 400);
        }
        
        return ['device_id' => $request->device_id];
    }
    
    /**
     * Get comparison list for current user or device
     */
    public function index(Request $request)
    {
        $identifier = $this->getIdentifier($request);
        
        // Handle error response
        if (isset($identifier['success']) && $identifier['success'] === false) {
            return $identifier;
        }
        
        $compareItems = CompareItem::where($identifier)->get();
        $productIds = $compareItems->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productIds)->get();
        
        return new ProductDetailCollection($products);
    }
    
    /**
     * Clear comparison list
     */
    public function reset(Request $request)
    {
        $identifier = $this->getIdentifier($request);
        
        // Handle error response
        if (isset($identifier['success']) && $identifier['success'] === false) {
            return $identifier;
        }
        
        CompareItem::where($identifier)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Compare list cleared successfully'
        ]);
    }
    
    /**
     * Add product to compare list
     */
    public function addToCompare(Request $request)
    {
        if (!$request->has('id')) {
            return response()->json([
                'success' => false,
                'message' => 'Product ID is required'
            ], 400);
        }
        
        $identifier = $this->getIdentifier($request);
        
        // Handle error response
        if (isset($identifier['success']) && $identifier['success'] === false) {
            return $identifier;
        }
        
        // Check if product exists
        $product = Product::find($request->id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        // Check if we already have 3 items
        $compareCount = CompareItem::where($identifier)->count();
        
        // If we already have the maximum, remove the oldest
        if ($compareCount >= 3) {
            $oldestItem = CompareItem::where($identifier)
                ->orderBy('created_at', 'asc')
                ->first();
                
            if ($oldestItem) {
                $oldestItem->delete();
            }
        }
        
        // Add new item
        $compareItem = new CompareItem;
        
        if (isset($identifier['user_id'])) {
            $compareItem->user_id = $identifier['user_id'];
        } else {
            $compareItem->device_id = $identifier['device_id'];
        }
        
        $compareItem->product_id = $request->id;
        
        // Use firstOrCreate to avoid duplicates
        try {
            $compareItem = CompareItem::firstOrCreate(
                array_merge($identifier, ['product_id' => $request->id]),
                []
            );
        } catch (\Exception $e) {
            // Item already exists, just return success
        }
        
        // Return updated compare list
        $compareItems = CompareItem::where($identifier)->get();
        $productIds = $compareItems->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productIds)->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to compare list',
            'compare_items' => $productIds,
            'products' => new ProductMiniCollection($products)
        ]);
    }
    
    /**
     * Get list of product IDs in compare list
     */
    public function getCompareList(Request $request)
    {
        $identifier = $this->getIdentifier($request);
        
        // Handle error response
        if (isset($identifier['success']) && $identifier['success'] === false) {
            return $identifier;
        }
        
        $compareItems = CompareItem::where($identifier)->get();
        $productIds = $compareItems->pluck('product_id')->toArray();
        
        return response()->json([
            'success' => true,
            'compare_items' => $productIds
        ]);
    }
    
    /**
     * Remove a product from compare list
     */
    public function removeFromCompare(Request $request)
    {
        if (!$request->has('id')) {
            return response()->json([
                'success' => false,
                'message' => 'Product ID is required'
            ], 400);
        }
        
        $identifier = $this->getIdentifier($request);
        
        // Handle error response
        if (isset($identifier['success']) && $identifier['success'] === false) {
            return $identifier;
        }
        
        CompareItem::where($identifier)
            ->where('product_id', $request->id)
            ->delete();
        
        // Return updated list
        $compareItems = CompareItem::where($identifier)->get();
        $productIds = $compareItems->pluck('product_id')->toArray();
        
        return response()->json([
            'success' => true,
            'message' => 'Product removed from compare list',
            'compare_items' => $productIds
        ]);
    }
}