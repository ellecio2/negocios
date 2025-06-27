<?php  
namespace App\Http\Resources\V2;  

use Illuminate\Http\Resources\Json\JsonResource; 
use Carbon\Carbon;  

class CouponShowResource extends JsonResource {     
    public function toArray($request)     {         
        return [             
            'id' => $this->id,             
            'code' => $this->code,             
            'type' => $this->type,             
            'details' => $this->details,             
            'discount' => $this->discount,             
            'discount_type' => $this->discount_type,             
            'start_date' => $this->start_date ? Carbon::parse($this->start_date)->format('Y-m-d H:i:s') : null,             
            'end_date' => $this->end_date ? Carbon::parse($this->end_date)->format('Y-m-d H:i:s') : null,             
            'start_timestamp' => $this->start_date ? Carbon::parse($this->start_date)->getTimestamp() : null,             
            'end_timestamp' => $this->end_date ? Carbon::parse($this->end_date)->getTimestamp() : null,         
        ];     
    } 
}