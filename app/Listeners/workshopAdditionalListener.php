<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\workshopAdditionalNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class workshopAdditionalListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $client_id  = $event->workshopAdditionalCharge->workshopServiceProposal->user_id;

        $usuario = User::find($client_id);

        Notification::send($usuario, new workshopAdditionalNotification($event->workshopAdditionalCharge));
    }
}
