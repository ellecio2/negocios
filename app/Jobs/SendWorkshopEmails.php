<?php

namespace App\Jobs;

use App\Mail\WorkshopEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendWorkshopEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $workshop_usuarios = User::where('add_user_type', 'workshop')->get();

        foreach ($workshop_usuarios as $usuario) {

            $token = Str::random(60);
            $usuario->updateOrCreate(['id' => $usuario->id], ['login_token' => $token]);

            Mail::to($usuario->email)->send(new WorkshopEmail($token));
        }
    }
}
