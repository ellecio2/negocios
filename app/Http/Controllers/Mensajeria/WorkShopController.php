<?php

namespace App\Http\Controllers\Mensajeria;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Delivery\MapsController;
use App\Models\BusinessWorkingHours;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\UserHasConversation;
use App\Models\WhatsappOpenedConversation;
use App\Models\Workshop;
use App\Models\WorkShopProposal;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class WorkShopController extends Controller {
    public static function startWorkshops() {
        $workshops = Workshop::whereHas('categories', function ($query){
            $query->where('name', 'Mecánica Automotriz');
        })->get();

        $workshops->each(function ($workshop){
            // Clear status
            $workshop->update([
                'is_available' => 'PENDIENTE'
            ]);
            //build message
            $message = [
                'messaging_product' => 'whatsapp',
                'to' => $workshop->user->phone,
                'type' => 'template',
                'template' => [
                    'name' => 'buenos_dias',
                    'language' => [
                        'code' => 'es'
                    ],
                    'components' => [
                        [
                            'type' => 'body',
                            'parameters' => [
                                [
                                    'type' => 'text',
                                    'text' => "$workshop->name"
                                ]
                            ]
                        ]
                    ]
                ],
            ];
            // Dispatch message to all workshops
            ( new WhatsAppController )->sendMessage($message);
        });
    }
    public static function setWorkshopAsAvailable(string  $phone) : void {
        $user = WhatsAppController::searchUserByPhone($phone, 'workshop');

        $user->workshop->update([
            'is_available' => 'DISPONIBLE'
        ]);

        $message = [
            'messaging_product' => 'whatsapp',
            'to' => $user->phone,
            'type' => 'text',
            'text' => [
                'body' => "*¡Gracias por responder!*\n\nEspera atento en el transcurso del día, las solicitudes de servicio de los clientes de *La Pieza.DO*.\n\n¡Feliz día!."
            ]
        ];

        // Dispatch message to all workshops
        ( new WhatsAppController )->sendMessage($message);
    }
    public static function setWorkshopAsNotAvailable(string  $phone) : void {
        $user = WhatsAppController::searchUserByPhone($phone, 'workshop');

        $user->workshop->update([
            'is_available' => 'NO DISPONIBLE'
        ]);

        $date = Carbon::now()->timezone('America/Santo_Domingo')->locale('es');
        $date->addDays(1);
//
        $message = [
            'messaging_product' => 'whatsapp',
            'to' => $user->phone,
            'type' => 'text',
            'text' => [
                'body' => "*¡Gracias por responder!*\n\nMañana *" . ucfirst($date->dayName) . ", $date->day de " . ucfirst($date->monthName) . "* te enviaremos nuevamente esta alerta para saber si estas disponible y puedas recibir las solicitudes de servicio de los clientes de *La Pieza.DO*.\n\n¡Feliz día!."
            ]
        ];

        // Dispatch message to all workshops
        ( new WhatsAppController )->sendMessage($message);
    }
    public static function sendRequestedDataToWorkShop( string $phone ) {
        // User that requested the service
        $user = WhatsAppController::searchUserByPhone($phone);
        // Primary conversation asociated
        $main_conversation = UserHasConversation::where('workshop_id', null)
            ->where('user_id', $user->id)
            ->first();
        // Avaiable Workshops in 3km around, type Mecánica Automotriz and Status "Disponible"
        $workshops = self::searchAroundWorkshops($user, $main_conversation);
        // If not workshops available we stops the process
        if($workshops->count() <= 0){
            self::notWorkshopsAvailable($user);
            return;
        }
        // Filter only workshops whitout process opened
        $workshops = $workshops->filter(function ($workshop){
            return !UserHasConversation::where('workshop_id', $workshop->id)->exists();
        });
        // Dispatch messages to the available workshops
        $workshops->each(function ($workshop) use ($main_conversation, $user){
            $request = json_decode($main_conversation->conversation->process, true);
            $products = collect();

            foreach($request['productsData'] as $product_data){
                $product = Product::find($product_data['productId']);
                $products->push($product);
            }

            $productsText = "";

            foreach($products as $product){
                $productsText .= "*{$product->name}*\n\n";
            }

            $proposal = WorkShopProposal::create([
                'note' => 'Nota por defecto',
                'price' => 0.0,
                'selected_day' => Carbon::now(),
                'combined_order_id' => $main_conversation->conversation->combinedOrder->id
            ]);

            UserHasConversation::create([
                'workshop_proposal_id' => $proposal->id,
                'workshop_id' => $workshop->id,
                'user_id' => $user->id,
                'conversation_id' => $main_conversation->conversation->id
            ]);

            $message = [
                'messaging_product' => 'whatsapp',
                'to' => $workshop->user->phone,
                'type' => 'interactive',
                'interactive' => [
                    'type' => 'button',
                    'header' => [
                        'type' => 'text',
                        'text' => 'Solicitud de Servicios'
                    ],
                    'body' => [
                        'text' => "Instalacion:\n{$productsText}Observaciones:\n{$request['description']}"
                    ],
                    'footer' => [
                        'text' => "Orden: {$main_conversation->conversation->combinedOrder->id}"
                    ],
                    'action' => [
                        'buttons' => [
                            [
                                'type' => 'reply',
                                'reply' => [
                                    'id' => "enviar-propuesta_$proposal->id",
                                    'title' => 'ENVIAR PROPUESTA'
                                ]
                            ]
                        ]
                    ]
                ]
            ];
            ( new WhatsAppController )->sendMessage($message);
        });
    }
    public static function sendProposeIndications(string $phone) {
        $user = WhatsAppController::searchUserByPhone($phone, 'workshop');

        $message = [
            'messaging_product' => 'whatsapp',
            'to' => $user->phone,
            'type' => 'text',
            'text' => [
                'body' => "*Enviar Propuesta 1/2*\n\n*ENVÍA EL MONTO DE LA PROPUESTA*\n\n*Nota:* Solo debes escribir números!\n\nSi escribes letras, no podrás ver el siguiente mensaje!"
            ]
        ];

        ( new WhatsAppController )->sendMessage($message);
    }
    public static function workshopSetAvailableDays(string $phone, string $message) {
        $workshopUser = WhatsAppController::searchUserByPhone($phone, 'workshop');

        $pivot_conversation = UserHasConversation::where('workshop_id', $workshopUser->workshop->id)
            ->where('user_id', '!=', null)
            ->first();

        Log::debug($pivot_conversation);
        $proposal = WorkShopProposal::find($pivot_conversation->workshop_proposal_id);

        $proposal->update([
            'price' => $message
        ]);

        $workingDays = BusinessWorkingHours::where('shop_id', $pivot_conversation->workshop->user->shop->id)->get();

        $now = Carbon::now();
        $days = [];

        for ($i = 1; $i < 8; $i++) {
            $fecha = clone $now;  // Clonar la fecha
            $fecha->addDays($i);
            $dia_semana_texto = $fecha->dayName;

            // Hacemos una copia de la fecha antes de cambiar la localización
            $fecha_es = clone $fecha;
            $dia_semana_texto_es = ucfirst($fecha_es->locale('es')->dayName);

            // Buscar el día de trabajo correspondiente
            $workingDay = $workingDays->firstWhere('dia_semana', ucfirst($dia_semana_texto));

            if ($dia_semana_texto !== 'Sunday' && $workingDay && $workingDay->laborable) {
                $mes = ucfirst($fecha_es->isoFormat('MMMM'));
                $days[] = [
                    'id' => $now,
                    'day_name' => $dia_semana_texto_es,
                    'day' => $fecha->day,
                    'month' => $mes,
                    'year' => $fecha->year,
                    'schedule' => $workingDay->hora_inicio . ' - ' . $workingDay->hora_fin,
                    'working' => $workingDay->laborable
                ];
            }
        }
        Log::debug(json_encode($days));

        $message = [
            'messaging_product' => 'whatsapp',
            'to' => $pivot_conversation->workshop->user->phone,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'list',
                'header' => [
                    'type' => 'text',
                    'text' => 'Enviar Propuesta 2/2',
                ],
                'body' => [
                    'text' => '¿Indicanos que dia puedes recibir al cliente?',
                ],
                'footer' => [
                    'text' => "Por favor selecciona un dia para recibir al cliente",
                ],
                'action' => [
                    'button' => 'SELECCIONA DÍA',
                    'sections' => [],
                ]
            ],
        ];

        foreach ($days as $day) {
            $message['interactive']['action']['sections'][] = [
                'title' => 'Seleccionar dia:',
                'rows' => [
                    [
                        'id' => "{$day['id']}_$proposal->id",
                        'title' => $day['day_name'],
                        'description' => $day['day'] . ' de ' . $day['month'] . ' del ' . $day['year'] . ' | ' . $day['schedule'],
                    ],
                ],
            ];
        }

        ( new WhatsAppController )->sendMessage($message);
    }
    public static function workshopFinishedProposeProcess(string $date_id) {

        $parts = explode('_', $date_id);
        $date = $parts[0];
        $proposal_id = $parts[1];

        $proposal = WorkShopProposal::find($proposal_id);

        $proposal->update([
            'selected_day' => Carbon::create($date)
        ]);

        $pivot_conversation = UserHasConversation::where('workshop_proposal_id', $proposal->id)->first();

        $message = [
            'messaging_product' => 'whatsapp',
            'to' => $pivot_conversation->workshop->user->phone,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'header' => [
                    'type' => 'text',
                    'text' => 'Propuesta Completada.'
                ],
                'body' => [
                    'text' => "Si requieres piezas adicionales, o tienes algún comentario para este servicio, las podrás cotizar o comentar en tu panel de taller!."
                ],
                'footer' => [
                    'text' => "¡En breve recibirás la respuesta del cliente!"
                ],
                'action' => [
                    'buttons' => [
                        [
                            'type' => 'reply',
                            'reply' => [
                                'id' => "finalizar_propuesta_$proposal->id",
                                'title' => 'FINALIZAR PROPUESTA'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        ( new WhatsAppController )->sendMessage($message);
    }
    private static function searchAroundWorkshops( User $user, $conversation ) : Collection {
        $userAddress = $user->addresses()->default()->first();
        // Only Mecanica automotriz de momento
        $worshops = self::searchWorkshopsByCategory($conversation, $user);

        return $worshops->filter(function (Workshop $workshop) use ($userAddress) {
            $workshopAddress = $workshop->user->addresses()->default()->first();
            $result = MapsController::getRouteData($userAddress, $workshopAddress);
            return $result['distance'] <= 10.0;
        });
    }
    private static function searchWorkshopsByCategory($conversation, User $user = null) : Collection {
        // TODO: A futuro esto filtrara las workshops en base a categorias dinamicas
        // Temporary only get workshops with "Mecanica automotriz" category
        return Workshop::where('is_available', 'DISPONIBLE')->whereHas('categories', function ($query){
            $query->where('name', 'Mecánica Automotriz');
        })->get();
    }
    private static function notWorkshopsAvailable(User $user) : void {
        $message = [
            'messaging_product' => 'whatsapp',
            'to' => $user->phone,
            'type' => 'text',
            'text' => [
                'body' => "*¡Lo sentimos!*\n\nParece que en este momento no contamos con talleres disponibles."
            ]
        ];
        // Delete main conversation
        UserHasConversation::where('user_id', $user->id)->where('workshop_id', null)->delete();
        (new WhatsAppController)->sendMessage($message);
    }
}
