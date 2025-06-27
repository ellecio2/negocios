<?php

namespace App\Notifications;

use App\Models\WorkshopServiceProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptServiceWorkshopProposalNotification extends Notification
{
    use Queueable;

    protected $workshopServiceProposal;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(WorkshopServiceProposal $workshopServiceProposal)
    {
        $this->workshopServiceProposal = $workshopServiceProposal;
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
            ///fecha que acepto el servicio
            'created_at' => $this->workshopServiceProposal->current_acceptance_request_date,
            ///nombre del cliente
            'name' => $this->workshopServiceProposal->user->name,
            ///id del la propuesta, donde cambio el campo a aceptado
            'id' => $this->workshopServiceProposal->id,
            ///orden de compra
            'order_code' => $this->workshopServiceProposal->order->code
        ];
    }
}
