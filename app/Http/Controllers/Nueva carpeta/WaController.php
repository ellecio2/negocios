<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WaController extends Controller
{
    public function envia(){

        $token = 'EAAKVLVU40eABO6TO9ZBIuovwz2pPnZB1F8PdJC6ZCKTZAtdzsvRthPb5bWaiFLLGuoKhq4fswOY8mYuJpia391RHXUwTLf6cZBMTXHEcaMp4SQE3Yr9vEwcNQo0KlH6Ucq1ZCHGXp6CNOdOHUdAP6dH7Kq7ebJZC3sg9utzZAZB54CgnBkTgAekzEW3YZAvLcCJWPvRcSEwaZAxMYfUuBX1nKgZD';

        $telefono = '8294014117';

        $url = 'https://graph.facebook.com/v17.0/167279663134149/messages';

        $mensaje = ''
                . '{'
                . '"messaging_product": "whatsapp", '
                . '"to": "'.$telefono.'", '
                . '"type": "template", '
                . '"template": '
                . '{'
                . '     "name": "hello_world",'
                . '     "language":{ "code": "en_US" } '
                . '}'
                . '}';
        
                
                $header = array("Autorization: Bearer " . $token, "Content-Type: application/json");

                $curl = curl_init();
                curl_setopt($curl, CUROPT_URL, $url);
                curl_setopt($curl, CUROPT_POSTFIELDS, $mensaje);
                curl_setopt($curl, CUROPT_HTTPHEADER, $header);
                curl_setopt($curl, CUROPT_RETURNTRANSFER, true);

                $response = json_decode(curl_exec($curl), true);

                print_r($response);

                $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                curl_close($curl);


    }

}