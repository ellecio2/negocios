<?php

namespace App\Http\Controllers\Api\V2;

use App\Mail\InvoiceEmailManager;
use App\Models\BusinessSetting;
use App\Models\CombinedOrder;
use App\Models\SellerPackage;
use App\Models\SellerPackagePayment;
use App\Models\User;
use App\Services\SellerPackageService;
use App\Utility\NotificationUtility;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Mail;
use Session;

class PagoAzulController extends Controller {
    private string $payment_url;
    private string $merchant_id;
    private string $merchant_name;

    public function __construct() {
        $this->payment_url = config('app.pago_azul_payment_url');
        $this->merchant_id = config('app.pago_azul_merchand_id');
        $this->auth_key = config('app.pago_azul_private_encrypt_hash');
        $this->merchant_name = config('app.name');
    }

    public function pay(Request $request) {
        if($request->token){
            $segments = explode('|', $request->token);
            $accessToken = PersonalAccessToken::where('token', hash('sha256', $segments[1]))->first();

            if ($accessToken) {
                // Si se encuentra el token, obtén el usuario asociado
                $user = $accessToken->tokenable;

                if ($user instanceof User) {
                    // Usuario encontrado
                    Auth::login($user);
                }
            }
        }

        $this->request = $request;

        if(isset($request->package_id)) {
            $request->session()->put('payment_type', 'seller_package');
            $this->sendPaymentData('seller_package');
        }else{
            $request->session()->put('payment_type', 'cart_payment');
            $this->sendPaymentData();
        }

    }

    private function sendPaymentData(string $type = 'cart_payment') {
        $body = $this->buildBody($type);

        // Inicio del formulario
        $form = "<form id='payment_form' action='{$this->payment_url}' method='post'>\n";

        // Campos del formulario
        foreach ($body as $name => $value) {
            // Aquí se genera el HTML para un campo oculto en el formulario
            $form .= $this->hiddenField($name, $value);
        }

        // Fin del formulario
        $form .= "</form>\n";
        $form .= "<script type='text/javascript'>document.getElementById('payment_form').submit();</script>";

        echo $form;
    }

    private function hiddenField($name, $value) {
        return sprintf("<input type='hidden' name='%s' value='%s'>\n", htmlspecialchars($name), htmlspecialchars($value));
    }


    private function buildBody(string $type){
        $amount = 0;
        $orderId = null;

        if($type == 'cart_payment'){
            $subtotal = 0;
            $shipping = 0;

            // Recuperar los carritos del usuario que esta comprando
            $carts = auth()->user()->carts->load('product');

            if($carts->count() <= 0){
                return response()->json([
                    'result' => 'false',
                    'error' => 'No hay nada que procesar'
                ], 403);
            }

            // La Pieza.Do comission value
            $vendor_commission = BusinessSetting::where('type', 'vendor_commission')->first()->value;

            // Recuperar el total de la orden
            foreach ($carts as $cart){
                $product_price = $cart->product->unit_price;
                $commission = $product_price * ( $vendor_commission / 100);
                $product_price = $product_price + $commission;

                $subtotal += $product_price;
                $shipping += $cart->shipping_cost;
            }

            $tax = $subtotal * (config('app.itbis') / 100);
            $amount = $subtotal + $tax + $shipping;
        }

        if($type == 'seller_package'){
            $package = SellerPackage::find($this->request->package_id);
            $orderId = $package->id;
            $amount = $package->amount;
        }

        $integerValue = round($amount * 100);
        $app_url = config('app.url');

        if($amount == 0){
            return response()->json([
                'result' => 'false',
                'error' => 'No hay nada que procesar'
            ], 403);
        }

        $merchData = [
            "MerchantId" => $this->merchant_id,
            "MerchantName" => $this->merchant_name,
            "MerchantType" => $type,
            "CurrencyCode" => "$",
            "OrderNumber" =>  auth()->id(),
            "Amount" => $integerValue,
            "ITBIS" => "000",
            "ApprovedUrl" => "$app_url/api/v2/payments/pay/azul/aproved/",
            "DeclinedUrl" => "$app_url/api/v2/payments/pay/azul/denied/",
            "CancelUrl" => "$app_url/api/v2/payments/pay/azul/canceled/",
            "UseCustomField1" => "0",
            "CustomField1Label" => "",
            "CustomField1Value" => "",
            "UseCustomField2" => "0",
            "CustomField2Label" => "",
            "CustomField2Value" => ""
        ];

        $merchData["AuthHash"] = $this->generateHMACAuthKey($merchData, $this->auth_key);

        return $merchData;
    }

    function generateHMACAuthKey(array $inputs, string $authKey): string {
        // Concatenando todos los inputs en un solo string
        $concatenated_string = $inputs['MerchantId']
            . $inputs['MerchantName']
            . $inputs['MerchantType']
            . $inputs['CurrencyCode']
            . $inputs['OrderNumber']
            . $inputs['Amount']
            . $inputs['ITBIS']
            . $inputs['ApprovedUrl']
            . $inputs['DeclinedUrl']
            . $inputs['CancelUrl']
            . $inputs['UseCustomField1']
            . $inputs['CustomField1Label']
            . $inputs['CustomField1Value']
            . $inputs['UseCustomField2']
            . $inputs['CustomField2Label']
            . $inputs['CustomField2Value']
            . $authKey;


        $concatenated_string = mb_convert_encoding($concatenated_string, 'UTF-8', 'ASCII');
        // Generando HMAC
        $hmac = hash_hmac('sha512', $concatenated_string, $authKey);
        return $hmac;
    }

    public function aproved( Request $request ) {
        $type = Session::get('payment_type');

        switch ($type){
            case 'cart_payment':
                    $request->merge([
                        'payment_type' => 'Azul'
                    ]);

                    $response = (new OrderController())->store($request, true);
                    $data = $response->getData();

                    if($data->result){
                        $combined_order = CombinedOrder::find($data->combined_order_id);
                        foreach ($combined_order->orders as $order) {
                            $order->update([
                                'payment_status' => 'paid',
                                'payment_details' => json_encode($request->all())
                            ]);

                            NotificationUtility::sendOrderPlacedNotification($order);

                            $array['view'] = 'emails.invoice';
                            $array['subject'] = 'Tu orden ah sido creada - ' . $order->code;
                            $array['from'] = env('MAIL_USERNAME');
                            $array['order'] = $order;

                            Mail::to(User::find($order->user_id)->email)->bcc($order->shop->user->email)->send(new InvoiceEmailManager($array));
                        }
                    }
                break;
            case 'seller_package':
                    $package_id = $request->input('OrderNumber');
                    $user = auth()->user();
                    $payment_details = $request->all();

                    // Make a purchase process of seller package
                    SellerPackageService::purchase($package_id, $user, $payment_details);
                break;
            default:
                break;
        }

        Session::flush();

        return view('form.aproved');
    }

    public function denied( Request $request ){
        if($request->input('ErrorDescription') == 'INSUF FONDOS'){
            return view('form.denied');
        }else{
            return view('form.denied');
        }
    }

    public function canceled(){
        return view('form.canceled');
    }
}
