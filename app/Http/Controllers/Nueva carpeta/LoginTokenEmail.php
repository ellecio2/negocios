<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginTokenEmail extends Controller
{
    //ESTE LOGIN ES PARA LOS CORREOS MASIVOS DE TALLER CORREO NUEVA SOLICITUD
    public function login($token)
    {
        $user = User::where('login_token', $token)->first();

        if ($user) {
            Auth::login($user);
            $user->update(['login_token' => null]);
            return redirect('/workshop/WorkshopService');
        } else {
            return redirect()->route('user.login');
        }
    }

    //ESTE LOGIN ES PARACORREO NUEVA PROPUESTA DE SERVICIO DISPONIBLE
    public function login_client($token)
    {
        $user = User::where('login_token', $token)->first();

        if ($user) {
            Auth::login($user);
            $user->update(['login_token' => null]);
            return redirect('Customer_Workshop_Request');
        } else {
            return redirect()->route('user.login');
        }
    }

    //ESTE LOGIN ES PARA ACEPTAR CORREO PROPUESTA DE SERVICIO DISPONIBLE
    public function login_taller($token)
    {
        $user = User::where('login_token', $token)->first();

        if ($user) {
            Auth::login($user);
            $user->update(['login_token' => null]);
            return redirect('/workshop/WorkshopService');
        } else {
            return redirect()->route('user.login');
        }
    }

}
