<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserCredentialsNotification extends Notification
{
    private $temporaryPassword;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($temporaryPassword)
    {
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->subject('Credenciales de cuenta')
            ->line('Muchas gracias por crear su cuenta. Aquí están sus credenciales:')
            ->line('Correo: ' . $notifiable->email)
            ->line('Contraseña: ' . $this->temporaryPassword)
            ->line('Por favor, asegúrese de cambiar su contraseña después de iniciar sesión.');
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
            //
        ];
    }
}
