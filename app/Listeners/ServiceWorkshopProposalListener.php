<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\ServiceWorkshopProposalNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notification;

class ServiceWorkshopProposalListener
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
        $client_id  = $event->workshopServiceProposal->user_id;

        $usuario = User::find($client_id);

        Notification::send($usuario, new ServiceWorkshopProposalNotification($event->workshopServiceProposal));
    }
}
