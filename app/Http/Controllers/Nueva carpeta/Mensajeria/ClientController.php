<?php

namespace App\Http\Controllers\Mensajeria;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\UserHasConversation;
use App\Models\WhatsappOpenedConversation;
use App\Models\WorkShopProposal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller {

    /**
     * Envía el mensaje inicial al cliente
     * @return void
     */
    public static function startClient(string | null $phone, $request){

        $order = Order::find($request['productsData'][0]['orderId']);
        $combined_order = $order->combinedOrder;

        // Search user in database
        $user = auth()->user();

        if(!$user){
            $user = WhatsAppController::searchUserByPhone($phone);
        }


        // Build the message to the client
        $message = [
            'messaging_product' => 'whatsapp',
            'to' => $user->phone,
            'type' => 'template',
            'template' => [
                'name' => 'acepta_cliente',
                'language' => [
                    'code' => 'es'
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => "$user->name"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Create new opened conversation
        $whatsapp_conversation = WhatsappOpenedConversation::create([
            'type' => 'workshop',
            'expiration_date' => Carbon::now()->addHours(24),
            'combined_order_id' => $combined_order->id,
            'process' => json_encode($request)
        ]);

        UserHasConversation::create([
            'user_id' => $user->id,
            'conversation_id' => $whatsapp_conversation->id,
        ]);

        // Send started message to the user
        ( new WhatsAppController )->sendMessage($message);

        flash(translate('Solicitud realizada con éxito!'))->success();
    }

    /**
     * Envía el mensaje de cancelación si el cliente cancela
     * el servicio
     * @return void
     */
    public static function clientCancelService(string $phone) {

        // Search user in database
        $user = WhatsAppController::searchUserByPhone($phone);

        $message = [
            'messaging_product' => 'whatsapp',
            'to' => $user->phone,
            'type' => 'text',
            'text' => [
                'body' => "*Haz CANCELADO, tu solicitud de Servicios*\n\nGracias por utilizar *La Pieza.DO!*"
            ]
        ];

        // Search main conversation
        $conversation = $user->conversations()->wherePivot('workshop_id', null)->first();

        // Send cancel confirmation message
        ( new WhatsAppController )->sendMessage($message);

        // Delete opened conversation
        WhatsAppOpenedConversation::find($conversation->pivot->conversation_id)->delete();
    }

    /**
     * Envía la propuesta al cliente
     * @return void
     */
    public static function sendProposeToClient(WorkShopProposal $proposal){
        $date = Carbon::createFromDate($proposal->selected_day)->locale('es');
        $formattedDay = $date->dayName . ', ' . $date->day . ' de ' . $date->monthName . ' del ' . $date->year;
        $pivot_conversation = UserHasConversation::where('workshop_proposal_id', $proposal->id)->first();
        $message = [
            'messaging_product' => 'whatsapp',
            'to' => "{$pivot_conversation->user->phone}",
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'header' => [
                    'type' => 'text',
                    'text' => '¡Tienes una propuesta de servicios!'
                ],
                'body' => [
                    'text' => "Propuesta :\n\n*Costo del Servicio:* \$$proposal->price\n*Fecha Disponible:* $formattedDay"
                ],
                'footer' => [
                    'text' => "{$proposal->combinedOrder->id} - Taller: LPDOT$proposal->id"
                ],
                'action' => [
                    'buttons' => [
                        [
                            'type' => 'reply',
                            'reply' => [
                                'id' => "aceptar-propuesta_$proposal->id",
                                'title' => 'ACEPTAR'
                            ]
                        ],
                        [
                            'type' => 'reply',
                            'reply' => [
                                'id' => "rechazar-propuesta_$proposal->id",
                                'title' => 'RECHAZAR'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        ( new WhatsAppController )->sendMessage($message);
    }

    /**
     * Envía la confirmación del servicio a ambas partes del trato
     * @return void
     */
    public static function sendServiceConfirmation($proposal_id){
        $proposal = WorkShopProposal::find($proposal_id);

        $pivot_conversation = UserHasConversation::where('workshop_proposal_id', $proposal->id)->first();

        $conversations = UserHasConversation::where('user_id', $pivot_conversation->user->id)
            ->where('id', '!=', $pivot_conversation->id)
            ->get();

        $conversations->each(function ($conversation){
            if($conversation->proposal){
                $message = [
                    'messaging_product' => 'whatsapp',
                    'to' => $conversation->workshop->user->phone,
                    'type' => 'text',
                    'text' => [
                        'body' => "*TU PROPUESTA HA SIDO RECHAZADA POR EL CLIENTE!*\n\nNo te desanimes, ya vienen mas solicitudes en camino.\n\nGracias por utilizar *La Pieza.DO!*"
                    ]
                ];

                ( new WhatsAppController )->sendMessage($message);

                $conversation->proposal->delete();
            }
        });

        // TODO: Cuando se construya el modulo de los talleres marcar la propuesta aceptada y guardarla a como sea pertinente
        $conversation = WhatsappOpenedConversation::find($pivot_conversation->conversation->id)->delete();

        $clientMessage = [
            'messaging_product' => 'whatsapp',
            'to' => $pivot_conversation->user->phone,
            'type' => 'text',
            'text' => [
                'body' => "*ACEPTASTE LA PROPUESTA* - No.*LPDOT$proposal->id*\n\nDirigete al siguiente link, para confirmar y pagar tu orden:\n\n*https://www.lapieza.do/iniciar-sesion*"
            ]
        ];

        $workshopMessage = [
            'messaging_product' => 'whatsapp',
            'to' => $pivot_conversation->workshop->user->phone,
            'type' => 'text',
            'text' => [
                'body' => "*Felicidades!!*\n\n*TU PROPUESTA HA SIDO ACEPTADA!* - Orden No.*LPDOT$proposal->id*\n\nDirigete a tu Panel De Taller o Dirigete al siguiente link, para completar los detalles de la orden:\n\n*https://www.lapieza.do/iniciar-sesion*"
            ]
        ];

        ( new WhatsAppController )->sendMessage($clientMessage);
        ( new WhatsAppController )->sendMessage($workshopMessage);
    }

    /**
     * Envía el mensaje de notificación de denegación de propuesta
     * a ambas partes
     * @return void
     */
    public static function sendServiceDenied( int $proposal_id ){

        $proposal = WorkShopProposal::find($proposal_id);
        $pivot_conversation = UserHasConversation::where('workshop_proposal_id', $proposal->id)->first();

        $user = WhatsAppController::searchUserByPhone($pivot_conversation->user->phone);
        $workshop = WhatsAppController::searchUserByPhone($pivot_conversation->workshop->user->phone, 'workshop');

        $clientMessage = [
            'messaging_product' => 'whatsapp',
            'to' => $user->phone,
            'type' => 'text',
            'text' => [
                'body' => "*LAMENTAMOS QUE NO HAYAS ACEPTADO ESTA PROPUESTA*\n\nEspera mientras las demas propuestas son enviadas a tu numero de WhatsApp."
            ]
        ];

        $workshopMessage = [
            'messaging_product' => 'whatsapp',
            'to' => $workshop->phone,
            'type' => 'text',
            'text' => [
                'body' => "*TU PROPUESTA HA SIDO RECHAZADA POR EL CLIENTE!*\n\nNo te desanimes, ya vienen mas solicitudes en camino.\n\nGracias por utilizar *La Pieza.DO!*"
            ]
        ];
        ( new WhatsAppController )->sendMessage($clientMessage);
        ( new WhatsAppController )->sendMessage($workshopMessage);

        // Delete proposal
        $proposal->delete();
        // Delete temporary conversation
        $pivot_conversation->delete();
    }

    private static function getUserPhone(User $user){
        if($user->phone){
            return $user->phone;
        }else{
            return $user->addresses()->where('set_default', 1)->first()->phone;
        }
    }

    private static function buildProcessMap(){
        return [
            '1' => [
                'description' => 'Mensaje de confirmación de solicitud de servicio',
                'status' => 'no enviado'
            ],
            '2' => [
                'description' => 'Mensaje de confirmación de servicio',
                'status' => 'no enviado'
            ],
        ];
    }
}
