<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\ServiceRequestNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class ServiceRequestListener
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
        $usuarios = User::where('user_type', 'workshop')->get();
        foreach ($usuarios as $usuario) {
            Notification::send($usuario,new ServiceRequestNotification($event->workshopClientRequest));
        } 
    }
}
