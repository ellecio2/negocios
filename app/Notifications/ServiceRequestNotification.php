<?php

namespace App\Notifications;

use App\Models\WorkshopClientRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceRequestNotification extends Notification
{
    use Queueable;

    protected $workshopClientRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(WorkshopClientRequest $workshopClientRequest)
    {
        $this->workshopClientRequest = $workshopClientRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            ///fecha de solicitud
            'created_at' => $this->workshopClientRequest->created_at,
            ///user_id quien realizo la solicitud
            'name' => $this->workshopClientRequest->user->name,
            ///id del la solicitud
            'id' => $this->workshopClientRequest->id,

            ///titulo 
            'title' => 'Nueva solicitud',

            //icono
            'icon' => 'lar la-address-card',
            //color
            'color' => '008FFD',
        ];
    }
}
