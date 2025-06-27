<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\VerificationCodeRequest;
use App\Mail\VerificationEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PhoneController extends Controller {
    public function check(Request $request) {
        return response()->json([
            'exists' => User::where('phone', $request->input('phone'))
                ->where('user_type', $request->user_type)
                ->exists()
        ]);
    }
    public function checktel(Request $request) {
        $exists = User::where('telefono_tienda', $request->input('telefono_tienda'))
            ->where('user_type', 'seller')
            ->exists();

        return response()->json([
            'exists' => (bool) $exists  // Forzar conversión a booleano
        ]);
    }
    public function verifiedPhone(VerificationCodeRequest $request) {
        $verification_code = '';

        for($i = 1; $i <= 6; $i++) {
            $verification_code .= $request->input('verification' . $i);
        }

        $user = auth()->user();
        // $user = Auth::user();
       
        if ($user->verification_code == $verification_code) {
            $user->update([
                'verification_code' => null,
                'phone_verified_at' => Carbon::now('America/Santo_Domingo')
            ]);
            Log::info('Userdentro if: ' . $user);
            $this->sendVerificationMail($user);

            return redirect()->route('shop.view.email.verification');
        } else {
            return back()->withErrors(['verification_code' => 'Código incorrecto.']);
        }
    }

    private function sendVerificationMail(User $user) : void {
        Log::info('Sending verification email to ' . $user->email);
        try {
           
            Mail::to($user->email)->send(new VerificationEmail($user));
        } catch (\Exception $e) {
            Log::error('Error al enviar el correo' .$e);
            $user->delete();
            if (!($e->getCode() >= 250 && $e->getCode() <= 252)) {
                back()->with('error', "¡ups! Parece haber un error con tu correo electronico, prueba con otro distinto");
                return;
            }
        }
    }
}
