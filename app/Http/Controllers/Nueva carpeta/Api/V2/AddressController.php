<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\AddressCollection;
use App\Http\Resources\V2\CitiesCollection;
use App\Http\Resources\V2\CountriesCollection;
use App\Http\Resources\V2\StatesCollection;
use App\Models\Address;
use App\Models\Cart;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class AddressController extends Controller {
    public function addresses(Request $request) {
        if($request->has('default') && $request->input('default') == true){
            $address = auth()->user()->addresses()->default()->get();
        }else{
            $address = auth()->user()->addresses;
        }

        return new AddressCollection($address);
    }

    public function createShippingAddress(Request $request) {

        // Quitar guiones y espacios del telefono
        $phone = str_replace(['-', ' '], '', $request->phone);

        // Agrega +1 al inicio si no está ya presente
        if (substr($phone, 0, 2) !== '+1') {
            $phone = '+1' . $phone;
        }

        // Valida que el número tenga 12 dígitos (10 dígitos más el '+1' al inicio)
        if (strlen($phone) !== 12) {
            return response()->json([
                'result' => false,
                'message' => translate('El teléfono debe ser de 10 dígitos')
            ]);
        }

        $address = new Address;
        $address->user_id = auth()->user()->id;

        $address->address = $request->address;
        $address->country = $request->country;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->postalCode = $request->postalCode;
        $address->phone = $phone;
        $address->longitude = $request->longitude;
        $address->latitude = $request->latitude;
        $address->save();

        return response()->json([
            'result' => true,
            'message' => translate('Información de envío agregada correctamente!')
        ]);
    }

    public function updateShippingAddress(Request $request) {
        $address = Address::find($request->id);
        $address->address = $request->address;
        $address->country_id = $request->country_id;
        $address->state_id = $request->state_id;
        $address->city_id = $request->city_id;
        $address->postal_code = $request->postal_code;
        $address->phone = $request->phone;
        $address->save();

        return response()->json([
            'result' => true,
            'message' => translate('Información de envío actualizada correctamente!')
        ]);
    }

    public function updateShippingAddressLocation(Request $request) {
        $address = Address::find($request->id);
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->save();

        return response()->json([
            'result' => true,
            'message' => translate('Ubicación en el Mapa actualizada correctamente!')
        ]);
    }


    public function deleteShippingAddress($id) {
        $address = Address::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if ($address == null) {
            return response()->json([
                'result' => false,
                'message' => translate('Dirección no encontrada')
            ]);
        }
        $address->delete();
        return response()->json([
            'result' => true,
            'message' => translate('Información de envío ha sido borrada')
        ]);
    }

    public function makeShippingAddressDefault(Request $request) {
        Address::where('user_id', auth()->user()->id)->update(['set_default' => 0]); //make all user addressed non default first

        $address = Address::find($request->id);
        $address->set_default = 1;
        $address->save();
        return response()->json([
            'result' => true,
            'message' => translate('Información de envío por defecto Actualizada!')
        ]);
    }

    public function updateAddressInCart(Request $request) {
        try {
            Cart::where('user_id', auth()->user()->id)->update(['address_id' => $request->address_id]);
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => translate('No se pudo guardar la dirección')
            ]);
        }
        return response()->json([
            'result' => true,
            'message' => translate('Dirección Guardada')
        ]);
    }


    public function getShippingInCart(Request $request) {

        $cart = Cart::where('user_id', auth()->user()->id)->first();

        $address = $cart->address;
        return new AddressCollection(Address::where('id', $address->id)->get());
        //    return  new AddressCollection($address);

    }

    public function updateShippingTypeInCart(Request $request) {
        try {
            $carts = Cart::where('user_id', auth()->user()->id)->get();


            foreach ($carts as $key => $cart) {

                $cart->shipping_cost = 0;

                if ($request->shipping_type == "pickup_point") {
                    $cart->shipping_type = "pickup_point";
                    $cart->pickup_point = $request->shipping_id;
                    $cart->carrier_id = 0;
                } else if ($request->shipping_type == "home_delivery") {
                    $cart->shipping_cost = getShippingCost($carts, $key);
                    $cart->shipping_type = "home_delivery";
                    $cart->pickup_point = 0;
                    $cart->carrier_id = 0;
                } else if ($request->shipping_type == "carrier_base") {
                    $cart->shipping_cost = getShippingCost($carts, $key, $cart->carrier_id);
                    $cart->shipping_type = "carrier";
                    $cart->carrier_id = $request->shipping_id;
                    $cart->pickup_point = 0;
                }
                $cart->save();

            }

        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => translate('No se pudo guardar la dirección')
            ]);
        }
        return response()->json([
            'result' => true,
            'message' => translate('Dirección de entrega guardada')
        ]);


    }


    public function getCities() {
        return new CitiesCollection(City::where('status', 1)->get());
    }

    public function getStates() {
        return new StatesCollection(State::where('status', 1)->get());
    }

    public function getCountries(Request $request) {
        $country_query = Country::where('status', 1);
        if ($request->name != "" || $request->name != null) {
            $country_query->where('name', 'like', '%' . $request->name . '%');
        }
        $countries = $country_query->get();

        return new CountriesCollection($countries);
    }

    public function getCitiesByState($state_id, Request $request) {
        $city_query = City::where('status', 1)->where('state_id', $state_id);
        if ($request->name != "" || $request->name != null) {
            $city_query->where('name', 'like', '%' . $request->name . '%');
        }
        $cities = $city_query->get();
        return new CitiesCollection($cities);
    }

    public function getStatesByCountry($country_id, Request $request) {
        $state_query = State::where('status', 1)->where('country_id', $country_id);
        if ($request->name != "" || $request->name != null) {
            $state_query->where('name', 'like', '%' . $request->name . '%');
        }
        $states = $state_query->get();
        return new StatesCollection($states);
    }
}
