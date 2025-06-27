<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ApprovalAdminAccount extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user) {
        $this->user = $user;

        $this->user->update([
            'confirmation_code' => Str::random(25),
        ]);
    }

    public function build(): self {
        return $this->view('emails.approval-admin-account')->with(['user' => $this->user])->subject('Tu negocio ahora está activo');
    }
}
