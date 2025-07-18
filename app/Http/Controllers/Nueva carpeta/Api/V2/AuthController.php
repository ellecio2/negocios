<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Mensajeria\WhatsAppController;
use App\Http\Controllers\OTPVerificationController;
use App\Http\Requests\Auth\VerificationCodeRequest;
use App\Mail\VerificationEmail;
use App\Models\BusinessSetting;
use App\Models\Customer;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\AppEmailVerificationNotification;
use Hash;
use GeneaLabs\LaravelSocialiter\Facades\Socialiter;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Socialite;
use App\Models\Cart;
use App\Rules\Recaptcha;
use App\Services\SocialRevoke;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;
use Str;

class AuthController extends Controller {
    public function signup(Request $request) {
        $messages = array(
            'name.required' => translate('Name is required'),
            'email_or_phone.required' => $request->register_by == 'email' ? translate('Email is required') : translate('Phone is required'),
            'email_or_phone.email' => translate('Email must be a valid email address'),
            'email_or_phone.numeric' => translate('Phone must be a number.'),
            'email_or_phone.unique' => $request->register_by == 'email' ? translate('The email has already been taken') : translate('The phone has already been taken'),
            'password.required' => translate('Password is required'),
            'password.confirmed' => translate('Password confirmation does not match'),
            'password.min' => translate('Minimum 6 digits required for password')
        );
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|min:6|confirmed',
            'email_or_phone' => [
                'required',
                Rule::when($request->register_by === 'email', ['email', 'unique:users,email']),
                Rule::when($request->register_by === 'phone', ['numeric', 'unique:users,phone']),
            ],
            'g-recaptcha-response' => [
                Rule::when(get_setting('google_recaptcha') == 1, ['required', new Recaptcha()], ['sometimes'])
            ]
        ], $messages);
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        $user = new User();
        $user->name = $request->name;
        if ($request->register_by == 'email') {
            $user->email = $request->email_or_phone;
        }
        if ($request->register_by == 'phone') {
            $user->phone = $request->email_or_phone;
        }
        $user->password = bcrypt($request->password);
        $user->verification_code = rand(100000, 999999);
        $user->save();
        $user->email_verified_at = null;
        if ($user->email != null) {
            if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
                $user->email_verified_at = date('Y-m-d H:m:s');
            }
        }
        if ($user->email_verified_at == null) {
            if ($request->register_by == 'email') {
                try {
                    $user->notify(new AppEmailVerificationNotification());
                } catch (\Exception $e) {
                }
            } else {
                $otpController = new OTPVerificationController();
                $otpController->send_code($user);
            }
        }
        $user->save();
        //create token
        $user->createToken('tokens')->plainTextToken;
        return $this->loginSuccess($user);
    }

    public function resendCode() {
        $user = Auth::user();

        $user->update([
            'verification_code' => str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT)
        ]);

        (new WhatsAppController)->sendVerificationMessage($user);

        return response()->json(['result' => true]);
    }

    public function confirmCode(VerificationCodeRequest $request) {
        $user = auth()->user();
        if ($user->verification_code == $request->verification_code) {
            $user->update([
                'verification_code' => null,
                'phone_verified_at' => Carbon::now('America/Santo_Domingo')
            ]);
            return response()->json([
                'result' => true,
                'message' => translate('Tu número telefonico ah sido verificado con exito'),
            ]);
        } else {
            return response()->json([
                'result' => false,
                'message' => translate('El código que has proporcionado no es valido, por favor, verifica e ingresalo nuevamente'),
            ], 422);
        }
    }

    public function login(Request $request) {
        /*$request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);*/
        $delivery_boy_condition = $request->has('user_type') && $request->user_type == 'delivery_boy';
        $seller_condition = $request->has('user_type') && $request->user_type == 'seller';
        if ($delivery_boy_condition) {
            $user = User::whereIn('user_type', ['delivery_boy'])
                ->where('email', $request->email)
                ->orWhere('phone', $request->email)
                ->first();
        } elseif ($seller_condition) {
            $user = User::whereIn('user_type', ['seller'])
                ->where('email', $request->email)
                ->orWhere('phone', $request->email)
                ->first();
        } else {
            $user = User::whereIn('user_type', ['customer'])
                ->where('email', $request->email)
                ->orWhere('phone', $request->email)
                ->first();
        }

        // if (!$delivery_boy_condition) {
        if (!$delivery_boy_condition && !$seller_condition) {
            // TODO: Averiguar que es esto
            /*if (\App\Utility\PayhereUtility::create_wallet_reference($request->identity_matrix) == false) {
                return response()->json(['result' => false, 'message' => 'Identity matrix error', 'user' => null], 401);
            }*/
        }

        if ($user != null) {
            if (!$user->banned) {
                if (Hash::check($request->password, $user->password)) {
                    return $this->loginSuccess($user);
                } else {
                    return response()->json(['result' => false, 'message' => translate('Contraseña o Usuario Incorrecto'), 'user' => null], 401);
                }
            } else {
                return response()->json(['result' => false, 'message' => translate('Tu Usuario esta Bloqueado'), 'user' => null], 401);
            }
        } else {
            return response()->json(['result' => false, 'message' => translate('Este Usuario no Existe'), 'user' => null], 401);
        }
    }

    public function user(Request $request) {
        return response()->json($request->user());
    }

    public function logout(Request $request){
        $user = request()->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return response()->json([
            'result' => true,
            'message' => translate('Sesión Cerrada Correctamente!')
        ]);
    }

    public function socialLogin(Request $request){
        if (!$request->provider) {
            return response()->json([
                'result' => false,
                'message' => translate('User not found'),
                'user' => null
            ]);
        }
        switch ($request->social_provider) {
            case 'facebook':
                $social_user = Socialite::driver('facebook')->fields([
                    'name',
                    'first_name',
                    'last_name',
                    'email'
                ]);
                break;
            case 'google':
                $social_user = Socialite::driver('google')
                    ->scopes(['profile', 'email']);
                break;
            case 'twitter':
                $social_user = Socialite::driver('twitter');
                break;
            case 'apple':
                $social_user = Socialite::driver('sign-in-with-apple')
                    ->scopes(['name', 'email']);
                break;
            default:
                $social_user = null;
        }
        if ($social_user == null) {
            return response()->json(['result' => false, 'message' => translate('No social provider matches'), 'user' => null]);
        }
        if ($request->social_provider == 'twitter') {
            $social_user_details = $social_user->userFromTokenAndSecret($request->access_token, $request->secret_token);
        } else {
            $social_user_details = $social_user->userFromToken($request->access_token);
        }
        if ($social_user_details == null) {
            return response()->json(['result' => false, 'message' => translate('No social account matches'), 'user' => null]);
        }
        $existingUserByProviderId = User::where('provider_id', $request->provider)->first();
        if ($existingUserByProviderId) {
            $existingUserByProviderId->access_token = $social_user_details->token;
            if ($request->social_provider == 'apple') {
                $existingUserByProviderId->refresh_token = $social_user_details->refreshToken;
                if (!isset($social_user->user['is_private_email'])) {
                    $existingUserByProviderId->email = $social_user_details->email;
                }
            }
            $existingUserByProviderId->save();
            return $this->loginSuccess($existingUserByProviderId);
        } else {
            $existing_or_new_user = User::firstOrNew(
                [['email', '!=', null], 'email' => $social_user_details->email]
            );
            $existing_or_new_user->user_type = 'customer';
            $existing_or_new_user->provider_id = $social_user_details->id;
            if (!$existing_or_new_user->exists) {
                if ($request->social_provider == 'apple') {
                    if ($request->name) {
                        $existing_or_new_user->name = $request->name;
                    } else {
                        $existing_or_new_user->name = 'Apple User';
                    }
                } else {
                    $existing_or_new_user->name = $social_user_details->name;
                }
                $existing_or_new_user->email = $social_user_details->email;
                $existing_or_new_user->email_verified_at = date('Y-m-d H:m:s');
            }
            $existing_or_new_user->save();
            return $this->loginSuccess($existing_or_new_user);
        }
    }
    public function loginSuccess($user, $token = null) {
        if (!$token) {
            $token = $user->createToken('API Token')->plainTextToken;
        }
        return response()->json([
            'result' => true,
            'message' => translate('Sesión Iniciada Correctamente!'),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => null,
            'user' => [
                'id' => $user->id,
                'type' => $user->user_type,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'avatar_original' => uploaded_asset($user->avatar_original),
                'phone' => $user->phone,
                'email_verified' => $user->email_verified_at != null
            ]
        ]);
    }

    protected function loginFailed() {
        return response()->json([
            'result' => false,
            'message' => translate('Login Failed'),
            'access_token' => '',
            'token_type' => '',
            'expires_at' => null,
            'user' => [
                'id' => 0,
                'type' => '',
                'name' => '',
                'email' => '',
                'avatar' => '',
                'avatar_original' => '',
                'phone' => ''
            ]
        ]);
    }

    public function account_deletion() {
        if (auth()->user()) {
            Cart::where('user_id', auth()->user()->id)->delete();
        }
        // if (auth()->user()->provider && auth()->user()->provider != 'apple') {
        //     $social_revoke =  new SocialRevoke;
        //     $revoke_output = $social_revoke->apply(auth()->user()->provider);
        //     if ($revoke_output) {
        //     }
        // }
        $auth_user = auth()->user();
        $auth_user->tokens()->where('id', $auth_user->currentAccessToken()->id)->delete();
        $auth_user->customer_products()->delete();
        User::destroy(auth()->user()->id);
        return response()->json([
            "result" => true,
            "message" => translate('Your account deletion successfully done')
        ]);
    }

    public function getUserInfoByAccessToken(Request $request) {
        $token = PersonalAccessToken::findToken($request->access_token);
        if (!$token) {
            return $this->loginFailed();
        }
        $user = $token->tokenable;
        if ($user == null) {
            return $this->loginFailed();
        }
        return $this->loginSuccess($user, $request->access_token);
    }

    public function passwordReset(Request $request){
        $request->validate([
            'password' => 'required|string',
            'confirm_password' => 'required|string|same:password',
        ], [
            'password.required' => 'El campo de contraseña es obligatorio.',
            'confirm_password.required' => 'Se requiere confirmación de la contraseña.',
            'confirm_password.same' => 'La confirmación de la contraseña debe coincidir con la contraseña ingresada.',
        ]);

        $user = auth()->user();
        $now = Carbon::now('America/Santo_Domingo');

        $user->update([
            'password' => bcrypt($request->input('password')),
            'referred_by' => null,
            'email_verified_at' => $now,
            'phone_verified_at' => $now,
            'correo_verified_at' => $now
        ]);

        return redirect()->route('dashboard');
    }

    public function resavePhone(Request $request){
        $user = auth()->user();

        $user->update([
            'phone' => $request->input('phone'),
            'verification_code' => str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT)
        ]);

        $user->addresses()->default()->first()->update(['phone' => $request->input('phone')]);

        (new WhatsAppController)->sendVerificationMessage($user);

        return response()->json([
            'result' => true,
            'message' => 'Tu número de teléfono ha sido actualizado con éxito.',
            'data' => [
                'phone' => $user->phone,
            ]
        ]);
    }

    public function resaveEmail(Request $request){
        $user = auth()->user();

        $user->update([
            'email' => $request->input('email'),
            'confirmation_code' => Str::random(25)
        ]);

        try {
            Mail::to($user->email)->send(new VerificationEmail($user));
        } catch (\Exception $e) {
            Log::error($e);
            if (!($e->getCode() >= 250 && $e->getCode() <= 252)) {
                return back()->with('error', "¡ups! Parece haber un error con tu correo electronico, prueba con otro distinto");
            }
        }

        return response()->json([
            'result' => true,
            'message' => 'Tu correo electronico se actualizo exitosamente.',
            'data' => [
                'email' => $user->email,
            ]
        ]);
    }

    public function accountSuccessfulVerified($token){
        $token = Crypt::decryptString($token);
        $user = User::where('confirmation_code', $token)->first();

        if (!$user) {
            return abort(403, 'El enlace es inválido o ha expirado.');
        }

        Auth::login($user);

        // Opcional: eliminar el token después de usarlo
        $user->update([
            'correo_verified_at' => now('America/Santo_Domingo'),
            'email_verified_at' => now('America/Santo_Domingo'),
            'confirmation_code' => null
        ]);

        $route = match(true){
            $user->user_type == 'seller' && $user->add_user_type == 'workshop' => 'workshop.dashboard',
            $user->user_type == 'seller' => 'seller.dashboard',
            $user->user_type == 'customer' => 'dashboard',
        };

        return redirect()->route($route);
    }
}
