<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreatedBySellerMail extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    public User $user;
    public string $password;
    public User $created_by;

    public function __construct(User $user, string $password, User $created_by) {
        $this->user = $user;
        $this->password = $password;
        $this->created_by = $created_by;
    }

    public function build() {
        return $this->subject('LaPieza.Do - ¡Bienvenido tu nueva cuenta ah sido creada!')
            ->markdown('emails.user-created-by-seller', [
                'user' => $this->user,
                'password' => $this->password,
                'creator' => $this->created_by
            ]);
    }
}
