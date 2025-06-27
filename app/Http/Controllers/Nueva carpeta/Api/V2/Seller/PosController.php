<?php

namespace App\Http\Controllers\Api\V2\Seller;

use App\Http\Controllers\Mensajeria\WhatsAppController;
use App\Http\Resources\PosProductCollection;
use App\Http\Resources\V2\AddressCollection;
use App\Http\Resources\V2\Seller\CartCollection;
use App\Http\Resources\V2\Seller\CustomerCollection;
use App\Mail\UserCreatedBySellerMail;
use App\Mail\VerificationEmail;
use App\Models\Address;
use App\Models\Cart;
use App\Models\User;
use App\Utility\PosUtility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Str;

class PosController extends Controller {
    public function productsList(Request $request) {
        $products = PosUtility::product_search($request->only('category', 'brand', 'keyword'));

        return response()->json([
            'products' => new PosProductCollection($products),
            'keyword' => $request->keyword,
            'category' => $request->category,
            'brand' => $request->brand
        ]);
    }

    public function getCustomers() {
        $customers = User::latest()->where('user_type', 'customer')->get();
        return new CustomerCollection($customers);
    }

    public function updateSessionUser(Request $request) {
        $userID = $request->userId;
        $sessionUserId = $request->sessionUserId;
        $sessionTemUserId = $request->sessionTemUserId;
        $carts = get_pos_user_cart($sessionUserId, $sessionTemUserId);

        // If user is selected but Session user is not this user
        if ($userID && $carts) {
            PosUtility::updatePosUserCartData($carts, $userID, null);
        }

        // If user is not selected, and if session has not Temp user ID
        if (!$userID) {
            if (!$sessionTemUserId) {
                $sessionTemUserId = bin2hex(random_bytes(10));
            }
            if ($carts) {
                PosUtility::updatePosUserCartData($carts, null, $sessionTemUserId);
            }
        }

        return response()->json([
            'result' => true,
            'message' => translate('Información actualizada'),
            'userID' => $userID,
            'temUserId' => $sessionTemUserId
        ]);
    }

    public function getShippingAddress($id) {
        $user = user::where('id', $id)->first();
        $shippingAddresses = $user->addresses;
        return new AddressCollection($shippingAddresses);
    }

    public function posConfigurationUpdate(Request $request) {
        $shop = auth()->user()->shop;
        $shop->thermal_printer_width = $request->thermal_printer_width;
        $shop->save();

        return $this->success(translate('Configuracion actualizada'));

    }

    public function posConfiguration(Request $request) {
        $shop = auth()->user()->shop;
        $data = $shop->thermal_printer_width;
        return $this->success($data);

    }

    public function createShippingAddress(Request $request) {
        $address = new Address;
        $address->user_id = $request->user_id;
        $address->address = $request->address;
        $address->country_id = $request->country_id;
        $address->state_id = $request->state_id;
        $address->city_id = $request->city_id;
        $address->postal_code = $request->postal_code;
        $address->phone = $request->phone;
        $address->save();

        return response()->json([
            'result' => true,
            'message' => translate('Información de entrega agregada con exito')
        ]);
    }

    public function addToCart(Request $request) {
        $stockId = $request->stock_id;
        $userID = $request->userID;
        $temUserId = $request->temUserId;
        if (!$temUserId && !$userID) {
            $temUserId = bin2hex(random_bytes(10));
        }
        $response = PosUtility::addToCart($stockId, $userID, $temUserId);

        return response()->json([
            'success' => $response['success'],
            'message' => $response['message'],
            'userId' => $userID,
            'temUserId' => $temUserId
        ]);
    }

    public function getUserCartData(Request $request) {
        $shippingCost = $request->shippingCost;
        $discountAmount = 0;
        $carts = Cart::where('user_id', $request->userId)->get();
        $subtotal = 0;
        $tax = 0;

        foreach ($carts as $cartItem) {
            $product = $cartItem->product;
            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
        }

        if ($request->discount) {
            $discountAmount = ($request->discount / 100) * ($subtotal);
            $subtotal = $subtotal - $discountAmount;
        }


        $tax = $subtotal * (config('app.itbis') / 100);

        $total = $subtotal + $tax + $shippingCost;

        return response()->json([
            'result' => true,
            'data' => [
                'cart_data' => new CartCollection($carts),
                'subtotal' => single_price($subtotal),
                'tax' => single_price($tax),
                'shippingCost' => $shippingCost,
                'shippingCost_str' => single_price($shippingCost),
                'discount' => single_price($discountAmount),
                'Total' => single_price($total)
            ]
        ]);
    }

    public function updateQuantity(Request $request) {
        $cart = Cart::find($request->cart_id);
        $response = PosUtility::updateCartItemQuantity($cart, $request->only(['cart_id', 'quantity']));

        return response()->json(['result' => (bool)$response['success'] ?? true, 'message' => $response['message']]);
    }

    public function removeFromCart(Request $request) {
        Cart::where('id', $request->id)->delete();
        return $this->success(translate('Eliminado exitosamente'));
    }

    public function orderStore(Request $request) {
        $response = PosUtility::orderStore($request->except(['_token']));
        return $response['success'] ? $this->success($response['message']) : $this->success($response['message']);
    }

    public function createUser(Request $request) {
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $confirmationCode = Str::random(25);

        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'referred_by' => auth()->id(),
                'password' => Hash::make($verificationCode),
                'user_type' => 'customer',
                'confirmation_code' => $confirmationCode,
                'verification_code' => $verificationCode,
            ]);
            Log::info('User created');
        }catch (Exception $e){
            Log::error('User cant be created');
            Log::error($e);
            return response()->json([
                'result' => false,
                'message' => 'No se puede crear el usuario, verifica los datos',
                'data' => []
            ]);
        }

        ( new WhatsAppController )->sendCreatedAccountMessage($user);

        try {
            Mail::to($user->email)->send(new VerificationEmail($user));
        } catch ( Exception $e) {
            Log::error('Mail cant be sendend');
            Log::error($e);
            $user->delete();
            return response()->json([
                'result' => false,
                'message' => 'No se ah podido enviar el correo electronico',
                'data' => []
            ]);
        }

        return response()->json([
            'result' => true,
            'message' => 'Usuario creado exitosamente',
            'data' => $user
        ]);
    }

    public function updateShippingAddress(Request $request){
        auth()->user()->addresses()->default()->delete();

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
                'message' => translate('El telefono debe ser de 10 digitos')
            ]);
        }

        $address = new Address;
        $address->user_id = auth()->user()->id;
        $address->address = $request->address;
        $address->country = $request->country;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->postalCode = $request->postalCode;
        $address->postal_code = $request->postalCode;
        $address->phone = $phone;
        $address->longitude = $request->longitude;
        $address->latitude = $request->latitude;
        $address->set_default = true;
        $address->save();

        return response()->json([
            'result' => true,
            'message' => translate('Direccion Guardada Exitosamente')
        ], 201);
    }
}


