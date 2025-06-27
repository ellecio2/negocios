<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Workshop;
use App\Notifications\AcceptServiceWorkshopProposalNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class AcceptServiceWorkshopProposalListener
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
        $workshop_id  = $event->workshopServiceProposal->workshop->id;

        $workshop = Workshop::find($workshop_id);

        $usuario = User::find($workshop->user_id);

        Notification::send($usuario, new AcceptServiceWorkshopProposalNotification($event->workshopServiceProposal));
    }
}
