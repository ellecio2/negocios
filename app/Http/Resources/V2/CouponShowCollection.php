<?php  
namespace App\Http\Resources\V2;  

use Illuminate\Http\Resources\Json\ResourceCollection;  
use App\Http\Resources\V2\CouponShowResource;

class CouponShowCollection extends ResourceCollection {     
    public function toArray($request)     {         
        return [             
            'data' => CouponShowResource::collection($this->collection),             
            'meta' => [                 
                'total_coupons' => $this->total(),                 
                'current_page' => $this->currentPage(),                 
                'from' => $this->firstItem(),                 
                'last_page' => $this->lastPage(),                 
                'path' => $request->url(),                 
                'per_page' => $this->perPage(),                 
                'to' => $this->lastItem(),                 
                'total' => $this->total()             
            ],             
            'links' => [                 
                'first' => $this->url(1),                 
                'last' => $this->url($this->lastPage()),                 
                'prev' => $this->previousPageUrl(),                 
                'next' => $this->nextPageUrl()             
            ]         
        ];     
    }      

    protected function paginationLinks()     {         
        $links = [];                  

        // Previous page link         
        $links[] = [             
            'url' => $this->previousPageUrl(),             
            'label' => '&laquo; Anterior',             
            'active' => false         
        ];          

        // Page numbers         
        for ($i = 1; $i <= $this->lastPage(); $i++) {             
            $links[] = [                 
                'url' => $this->url($i),                 
                'label' => (string)$i,                 
                'active' => $this->currentPage() === $i             
            ];         
        }          

        // Next page link         
        $links[] = [             
            'url' => $this->nextPageUrl(),             
            'label' => 'Siguiente &raquo;',             
            'active' => false         
        ];          

        return $links;     
    } 
}