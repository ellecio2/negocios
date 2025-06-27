<?php

namespace App\Http\Controllers\Workshop;

use App\Http\Controllers\Api\V2\OrderController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Mensajeria\ClientController;
use App\Models\Order;
use App\Models\UserHasConversation;
use Illuminate\Http\Request;

class WorkshopClientRequestController extends Controller {
    public function store(Request $request) {

        $response = (new OrderController)->workshopRequestStatus();
        $result = json_decode($response->getContent(), true);

        if(!$result['userHasOpenedProcess']){
            ClientController::startClient(auth()->user()->phone, $request->all());

            return response()->json([
                'message' => 'success',
            ]);
        }else{
            return response()->json([
                'message' => 'Tienes una petición en proceso, revisa tu whatsapp y terminala antes de solicitar otra'
            ], 400);
        }
    }

    public function cancelRequestService(){

        if(UserHasConversation::where('user_id', auth()->id())->exists()){
            $conversation = UserHasConversation::where('user_id', auth()->id())->first();

            $conversation->conversation->delete();

            return response()->json([
                'status' => 'success',
                'message' => "User now hasn't request service in progress"
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => "User hasn't request service in progress"
            ], 404);
        }

    }
}
