<?php  
namespace App\Models;  

use Carbon\Carbon; 
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Support\Facades\Log;  

class CouponShow extends Model {     
    protected $table = 'coupons';      

    protected $fillable = [
        'type',          
        'code',          
        'details',          
        'discount',          
        'discount_type',          
        'start_date',          
        'end_date'     
    ];      

    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at'
    ];
    
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    // Mutator for details
    public function getDetailsAttribute($value)     {         
        if (is_string($value)) {             
            $parsed = json_decode($value, true);             
            if (json_last_error() === JSON_ERROR_NONE) {                 
                return $parsed;             
            }         
        }         
        return $value;     
    }      

    // Date serialization
    protected function serializeDate(\DateTimeInterface $date)     {         
        return $date->format('Y-m-d H:i:s');     
    }      

    // Scope for available coupons
    public function scopeAvailable($query)
{
    $now = Carbon::now()->toDateTimeString(); // Convert to string explicitly
    
    return $query->where('start_date', '<=', $now)
                 ->where('end_date', '>=', $now);
}
}