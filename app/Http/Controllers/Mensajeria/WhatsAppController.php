<?php

namespace App\Http\Controllers\Mensajeria;

use App\Classes\WhatsAppMessage;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\BusinessSetting;
use App\Models\User;
use App\Models\UserHasConversation;
use App\Models\WorkShopProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class WhatsAppController extends Controller
{
//    private const TOKEN = 'EAAEdR4iWMnQBO6QxSdCZC7o2aAGAh64uLkmZCsJVqX4oCBYfhF1WaacwcOuKwTUauVsA2RVwsZBxabM1lzNYJRZASecMmZCw0jw2BXYOZCPcpZBy7cWxT0X8nZBbVZAfjVNn2YDLjAQ2wYieL0Dzz7OhA6UFpclb22l3QqHVMTMJlPeA7zCsTOJR2klG7g6QHmgbJefAT6KITUr91ryFp';
//    private const PHONE_NUMBER_ID = '220567651146286';
    private const PHONE_NUMBER_ID = '165221420014421';
    private const VERSION = "v21.0";
    private const SEND_MESSAGE_URL = 'https://graph.facebook.com/' . self::VERSION . '/' . self::PHONE_NUMBER_ID . '/messages';
    private string $api_token;
    private array $headers;
    private const CLIENT_CONFIRM = 'SI, CONFIRMAR!';
    private const CLIENT_REJECT = 'NO, CANCELAR!';
    private const WORKSHOP_ACCEPT = 'SI PUEDO';
    private const WORKSHOP_REJECT = 'NO ESTARÉ DISPOPNIBLE';
    private const WORKSHOP_SEND_PROPOSAL = 'ENVIAR PROPUESTA';
    private const WORKSHOP_FINISHED_PROPOSAL = 'FINALIZAR PROPUESTA';
    private const CLIENT_ACCEPT_PROPOSAL = 'ACEPTAR';
    private const CLIENT_REJECT_PROPOSAL = 'RECHAZAR';

    public function __construct()
    {
        $this->api_token = config('app.whatsapp_api_token', 'EAAEdR4iWMnQBO75blnibahkpX7CZAZCCaDQ0BTXKt1rjoSD4wm3lr1wxoKLzotvqaXRisv39RYwrFghSoGDvcJQ3m33Nss6APionRFOJFVrGonbZCz7G8ufiXNSnkwLl8AUPgbAxEFP0Y8nyUCHyGsH1JKDmobvP3WQezjbkHHZB5ApfXxqG4bDbRhoH65NpzAZDZD');

        $this->headers = [
            "Authorization: Bearer " . $this->api_token,
            "Content-Type: application/json",
            "Accept: application/json"
        ];
    }

    public function sendMessage(mixed $message)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::SEND_MESSAGE_URL);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($message));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //TODO: Verificar certificados
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            //Log::error('Error al enviar mensaje whatsapp: ' . $error);
            return response()->json(['error' => 'Error al enviar mensaje: ' . $error]);
        }

        curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $response = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = json_last_error_msg();
            //Log::error('Error al decodificar respuesta whatsApp : ' . $error);
            return response()->json(['error' => 'Error al decodificar respuesta: ' . $error]);
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        //Log::debug("Response al enviar mensaje whatsApp: " . json_encode($response));
        return response()->json(['message' => 'EVENT_RECEIVED']);
    }

    private function markMessageAsRead($message_id)
    {
        $data = array(
            "messaging_product" => "whatsapp",
            "status" => "read",
            "message_id" => "$message_id",
        );

        $curl = curl_init(self::SEND_MESSAGE_URL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);

        $response = curl_exec($curl);

        curl_close($curl);
    }

    public function dispatchAction(Request $request): void
    {
        $data = $this->getUserResponse($request);
        $message = $data['message'];
        $date_id = $data['selectedDate'];
        $wasMatched = true;

        // Search entry user by phone provided from whatsapp
        $user = self::searchUserByPhone($data['phone']);

        // If entre user not found search a workshop by the phone provided from whatapp
        if (!$user) {
            $user = self:: searchUserByPhone($data['phone'], 'workshop');
        }

        //log::info('whatsApp dispatchAction message: ' . print_r($request, true));

        // If user and workshop isn't found stop the function
        if (!$user) return;

        switch ($message) {
            case self::CLIENT_CONFIRM:
                // Search if the user have and opened conversation
                if (!$this->checkIfClientHasAnOpenedConversation($data['phone'])) return;
                WorkShopController::sendRequestedDataToWorkShop($data['phone']);
                break;
            case self::CLIENT_REJECT:
                if (!$this->checkIfClientHasAnOpenedConversation($data['phone'])) return;
                ClientController::clientCancelService($data['phone']);
                break;
            case self::WORKSHOP_ACCEPT:
                if ($this->checkIfWorkshopWasAnswerDailyAvaiability($data['phone'])) return;
                WorkShopController::setWorkshopAsAvailable($data['phone']);
                break;
            case self::WORKSHOP_REJECT:
                if ($this->checkIfWorkshopWasAnswerDailyAvaiability($data['phone'])) return;
                WorkShopController::setWorkshopAsNotAvailable($data['phone']);;
//                WorkShopController::workShopDeniedService( $data['phone'] );
                break;
            case self::WORKSHOP_SEND_PROPOSAL:
                if (!$this->checkIfClientHasAnOpenedConversation($data['phone'], 'workshop')) return;
                WorkShopController::sendProposeIndications($data['phone']);
                break;
            case self::WORKSHOP_FINISHED_PROPOSAL:
                if (!$this->checkIfClientHasAnOpenedConversation($data['phone'], 'workshop')) return;
                $proposal = WorkShopProposal::find($data['conversation_id']);
                // TODO: Marcar la propuesta como finalizada
                ClientController::sendProposeToClient($proposal);
                break;
            case self::CLIENT_ACCEPT_PROPOSAL:
                if (!$this->checkIfClientHasAnOpenedConversation($data['phone'])) return;
                ClientController::sendServiceConfirmation($data['conversation_id']);
                break;
            case self::CLIENT_REJECT_PROPOSAL:
                if (!$this->checkIfClientHasAnOpenedConversation($data['phone'])) return;
                ClientController::sendServiceDenied($data['conversation_id']);
                break;
            default:
                $wasMatched = false;
        }

        // La expresión regular que coincide con el formato número
        $regex = '/^\d+$/';

        // Case any response match whit the options
        if (!$wasMatched) {
            // Case recieve a dateId
            if (!is_null($date_id)) {
                if (!$this->checkIfClientHasAnOpenedConversation($data['phone'], 'worshop')) return;
                WorkShopController::workshopFinishedProposeProcess($date_id);
            }
            // Case recieve a quantity
            if (preg_match($regex, $message)) {
                if (!$this->checkIfClientHasAnOpenedConversation($data['phone'], 'worshop')) return;
                WorkShopController::workshopSetAvailableDays($data['phone'], $message);
            }
        }
    }

    private function getUserResponse($request)
    {
        //Log::debug("Recibido en el webhook whatsApp " . print_r($request, true));
        //Log::debug($request->entry[0]);

        // Check if the entry is a message from the user
        if (isset($request->entry[0]['changes'][0]['value']['messages'])) {
            $entry = $request->entry[0]['changes'][0]['value']['messages'][0];
            $message = null;
            $id = null;
            $selectedDate = null;
            $conversation_id = null;
            $phone = $entry['from'];

            if (isset($entry['text']['body'])) {
                $message = $entry['text']['body'];
                $id = $entry['id'];
            } else if (isset($entry['interactive']['button_reply']['title'])) {
                $parts = explode('_', $entry['interactive']['button_reply']['id']);
                $conversation_id = end($parts);
                $message = $entry['interactive']['button_reply']['title'];
                $id = $entry['id'];
            } else if (isset($entry['interactive']['list_reply']['description'])) {
                $message = $entry['interactive']['list_reply']['description'];
                $selectedDate = $entry['interactive']['list_reply']['id'] ?? null;
                $id = $entry['id'];
            } else if (isset($entry['button']['text'])) {
                $message = $entry['button']['text'];
                $id = $entry['id'];
            }

            $this->markMessageAsRead($id);
        }

        return [
            'message' => $message ?? null,
            'selectedDate' => $selectedDate ?? null,
            'id' => $id ?? null,
            'phone' => $phone ?? null,
            'conversation_id' => $conversation_id ?? null
        ];
    }

    public static function searchUserByPhone(string|null $phone, string $type = 'user')
    {

        if (is_null($phone)) return null;

        //log::info('whatsApp searchUserByPhone phone: ' . $phone . $type);

        if ($type == 'workshop') {
            $user = User::has('workshop')->where('phone', 'LIKE', "%$phone%")->first();
        } else {
            $user = User::doesntHave('workshop')->where('phone', 'LIKE', "%$phone%")->first();
        }

        if (!$user) {
            $address = Address::where('phone', 'LIKE', "%$phone%")
                ->where('set_default', 1)
                ->first();

            $address->user->update(['phone' => $address->phone]);

            if ($type == 'workshop') {
                $user = $address->user()->has('workshop')->first();
            } else {
                $user = $address->user()->doesntHave('workshop')->first();
            }
        }

        return $user;
    }

    public function checkIfClientHasAnOpenedConversation(string|null $phone, string $type = 'user'): bool
    {
        if ($type == 'user') {
            $user = self::searchUserByPhone($phone);
            $conversation = $user->conversations()->wherePivot('workshop_id', null)->first();

            if (is_null($conversation)) {
                return false;
            }
        } else {
            $user = self::searchUserByPhone($phone, 'workshop');
            $conversations = UserHasConversation::where('workshop_id', $user->workshop->id)->count();

            if ($conversations <= 0) {
                return false;
            }
        }

        return true;
    }

    public function checkIfWorkshopWasAnswerDailyAvaiability($phone): bool
    {
        $user = self::searchUserByPhone($phone, 'workshop');

        return $user->workshop->is_available == 'DISPONIBLE' || $user->workshop->is_available == 'NO DISPONIBLE';
    }

    public function sendVerificationMessage(User $user)
    {
        //log::info('whatsApp sendVerificationMessage user: ' . print_r($user, true));
        $this->sendMessage([
            'messaging_product' => 'whatsapp',
            'to' => $user->phone,
            'type' => 'template',
            'template' => [
                'name' => 'confirmacion_movil',
                'language' => [
                    'code' => 'es'
                ],
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => [
                            [
                                "type" => "text",
                                "text" => "$user->verification_code"
                            ]
                        ]
                    ],
                    [
                        "type" => "button",
                        "sub_type" => "url",
                        'index' => "0",
                        "parameters" => [
                            [
                                "type" => "text",
                                "text" => "$user->verification_code"
                            ]
                        ]
                    ]
                ],
            ],
        ]);
    }

    public function sendCreatedAccountMessage(User $user)
    {
        $url = URL::signedRoute(
            'verification.verify',
            ['id' => $user->id, 'hash' => $user->confirmation_code]
        );

        $fullUrl = $url;
        $baseUrl = config('app.url');
        $urlSegment = str_replace($baseUrl, "", $fullUrl);
        $fullUrl = $urlSegment;
        $baseUrl = "/email/verify/";
        $urlSegment = str_replace($baseUrl, "", $fullUrl);

        $this->sendMessage([
            'messaging_product' => 'whatsapp',
            'to' => $user->phone,
            'type' => 'template',
            'template' => [
                'name' => 'user_generados',
                'language' => [
                    'code' => 'es'
                ],
                'components' => [
                    [
                        'type' => 'button',
                        'sub_type' => 'url',
                        'index' => '0',
                        'parameters' => [
                            [
                                "type" => "text",
                                "text" => "$urlSegment"
                            ],
                        ]
                    ]
                ]
            ],
        ]);
    }
}
