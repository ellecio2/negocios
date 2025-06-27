<?php  
namespace App\Http\Controllers\Api\V2;  

use App\Http\Controllers\Controller; 
use App\Models\CouponShow; 
use App\Http\Resources\V2\CouponShowCollection; 
use App\Http\Resources\V2\CouponShowResource; 
use Illuminate\Http\Request; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Log;  
use App\Http\Controllers\Api\V2\ImageController;

class CouponShowController extends Controller {     
    public function index(Request $request)
    {
        try {
            $now = Carbon::now()->toDateTimeString();
            
            $query = CouponShow::query();
            
            $query->where('start_date', '<=', $now)
                  ->where('end_date', '>=', $now);
            
            if ($request->has('type')) {
                $query->where('type', $request->input('type'));
            }
            
            $query->orderBy('created_at', 'desc');
            
            $perPage = max(1, (int)$request->input('per_page', 15));
            $coupons = $query->paginate($perPage);
            
            return new CouponShowCollection($coupons);
        } catch (\Exception $e) {
            Log::error('Coupon List Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show(CouponShow $couponShow)     {         
        try {             
            $now = Carbon::now(); // Explicitly use Carbon::now()
            
            // Check if coupon is available
            if ($now < $couponShow->start_date || $now > $couponShow->end_date) {                 
                return response()->json([                     
                    'message' => 'Coupon not available'                 
                ], 404);             
            }              
            
            return new CouponShowResource($couponShow);         
        } catch (\Exception $e) {             
            Log::error('Error in coupon details', [                 
                'message' => $e->getMessage(),                 
                'trace' => $e->getTraceAsString()             
            ]);              
            
            return response()->json([                 
                'message' => 'Internal server error',                 
                'error' => $e->getMessage()             ], 500);         
        }     
    } 
}