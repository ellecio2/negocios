<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountSuccessfulVerifiedMail extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function build(): self {
        return $this->view('emails.account-successful-verified')->with(['user' => $this->user])->subject('Cuenta verificada con éxito');
    }
}
