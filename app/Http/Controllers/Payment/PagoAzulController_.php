<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Controller;
use App\Models\CombinedOrder;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\WalletController;
use App\Models\BusinessSetting;

class PagoAzulController extends Controller {

    private string $payment_url;
    private string $merchant_id;
    private string $merchant_name;
    private int $enviroment;
    private Request $request;

    public function __construct() {
        /*$this->payment_url = config('app.pago_azul_payment_url');
        $this->merchant_id = config('app.pago_azul_merchand_id');
        $this->auth_key = config('app.pago_azul_private_encrypt_hash');
        $this->merchant_name = config('app.name');*/

        $this->payment_url = config('app.dev_pago_azul_payment_url');
        $this->merchant_id = config('app.dev_pago_azul_merchand_id');
        $this->auth_key = config('app.dev_pago_azul_private_encrypt_hash');
        $this->merchant_name = config('app.name');
    }

    /*public function __construct()
    {
        $this->enviroment = BusinessSetting::where('type', 'pago_azul_payment')->first()->value;

        if ($this->enviroment == 1) {
            $this->payment_url = config('app.pago_azul_payment_url');
            $this->merchant_id = config('app.pago_azul_merchand_id');
            $this->auth_key = config('app.pago_azul_private_encrypt_hash');
            $this->merchant_name = config('app.name');
        } else {
            $this->payment_url = config('app.dev_pago_azul_payment_url');
            $this->merchant_id = config('app.dev_pago_azul_merchand_id');
            $this->auth_key = config('app.dev_pago_azul_private_encrypt_hash');
            $this->merchant_name = config('app.name');
        }
    }*/

    public function pay(Request $request) {
        $this->request = $request;
        $this->sendPaymentData($request);
    }

    private function sendPaymentData(Request $request) {
        $payment_data = $request->session()->get('payment_data');
        $payment_type = $request->session()->get('payment_type');
        $body = null;

        if($payment_type != 'wallet_payment'){
            $body = $this->buildBody();
        }else{
            $body =  $this->buildBody(true, $payment_data );
        }

        // Creamos el form a enviar
        $form = "<form id='payment_form' action='{$this->payment_url}' method='post'>";
        foreach ($body as $key => $value)
        {
            $form .= "<input type='hidden' name='{$key}' value='{$value}'>";
        }
        $form .= "</form>";
        //$form .= "<script type='text/javascript'>document.getElementById('payment_form').submit();</script>";

        echo $form;
    }

    private function buildBody($wallet_payment = false, $payment_data = []){
        $combinedOrder = CombinedOrder::find($this->request->session()->get('combined_order_id'));
        $wallet = null;

        $amount = $combinedOrder->grand_total ?? 0;
        $integerValue = $wallet_payment ? round($payment_data['amount'] * 100) : round($amount * 100);
        $app_url = config('app.url');

        if($wallet_payment){
            $wallet = Wallet::create([
                'user_id' => auth()->id(),
                'amount' => $payment_data['amount'],
                'approval' => 0,
                'offline_payment' => 0,
                'payment_method' => $payment_data['payment_method'],
                'payment_details' => 'wallet recharge',
                'reciept' => null
            ]);
        }

        $merchData = [
            "MerchantId" => $this->merchant_id,
            "MerchantName" => $this->merchant_name,
            "MerchantType" => $wallet_payment ? 'wallet_payment' : "ECommerce",
            "CurrencyCode" => "$",
            "OrderNumber" =>  $wallet_payment ? $wallet->id : $combinedOrder->id,
            "Amount" => $integerValue,
            "ITBIS" => "000",
            "ApprovedUrl" => "$app_url/pago-azul/aproved/",
            "DeclinedUrl" => "$app_url/pago-azul/denied/",
            "CancelUrl" => "$app_url/pago-azul/canceled/",
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

    // 4035874000424977 Exp. 1224 cvv. 977 // sin fondos
    // 5426064000424979 Exp. 1224 cvv. 979 // sin fondos
    // 4012000033330026 Exp. 1224 cvv. 123 // Aprobada
    // 5424180279791732 Exp. 1224 cvv. 732 // Tarjeta Internacional
    // 6011000990099818 Exp. 1224 cvv. 818 // Aprobada
    // 4260550061845872 Exp. 1224 cvv. 872 // Tarjeta internacional

    public function aproved( Request $request ) {
        $payment_type = $request->session()->get('payment_type');

        if($payment_type == 'wallet_payment'){
            $wallet = Wallet::find($request->OrderNumber);
            return (new WalletController)->wallet_payment_done($wallet, $request);
        }

        $request->session()->forget('combined_order_id');

        return ( new CheckoutController )->checkout_done($request->input('OrderNumber'), $request->all());
    }

    public function denied( Request $request ){
        if($request->input('ErrorDescription')){
            flash("Transaccion fallida - {$request->input('ResponseMessage')}")->error();
        }else{
            flash($request->input('Transaccion fallida'))->error();
        }

        return redirect()->route('checkout.shipping_info');
    }

    public function canceled( Request $request ){
        return redirect()->route('checkout.shipping_info');
    }
}
