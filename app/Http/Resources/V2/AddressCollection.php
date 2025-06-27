<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\User;
class AddressCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                   $user = User::find($data->user_id);

                // $location_available = false;
                // $lat = 90.99;
                // $lang = 180.99;

                // if($data->latitude || $data->longitude) {
                //     $location_available = true;
                //     $lat = floatval($data->latitude) ;
                //     $lang = floatval($data->longitude);
                // }

                return [
                    'id' => (int) $data->id,
                    'user_id' => (int) $data->user_id,
                    'name'=> $user ? $user->name : null,
                    'email' => $data->email ?? ($user ? $user->email : null),
                    'address' => $data->address,
                    'country' => $data->country,
                    'state' => $data->state,
                    'city' => $data->city,                    
                    'country' => $data->country,
                    'state' => $data->state,
                    'city' => $data->city,
                    'postalCode' => $data->postalCode,
                    'phone' => $data->phone,
                    'set_default' => (int) $data->set_default,
                    // 'location_available' => $location_available,
                    'longitude' => $data->longitude,
                    'latitude' => $data->latitude,
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
