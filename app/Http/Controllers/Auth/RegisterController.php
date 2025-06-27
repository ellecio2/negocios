<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api\V2\FileUploadController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Mensajeria\WhatsAppController;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\SellerRequest;
use App\Models\Address;
use App\Models\CategoryTranslation;
use App\Models\SellerPackage;
use App\Models\Shop;
use App\Models\User;
use App\Models\Workshop;
use App\Models\WorkshopCategory;
use Auth;
use DateTime;
use DOMDocument;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function sellerView()
    {
        return view('frontend.registro-comercio.views.business.index', ['categories' => $categories = CategoryTranslation::all()]);
    }

    public function workshopView()
    {
        return view('frontend.registro-comercio.views.workshops.index', ['categories' => $categories = WorkshopCategory::all()]);
    }

    // public function registerCustomer(RegisterRequest $request)
    // {
    //     \Log::info('=== INICIO REGISTRO CUSTOMER ===');

    //     try {
    //         $opened = User::where('email', $request->input('email'))
    //             ->where(function ($query) {
    //                 $query->whereNull('email_verified_at')
    //                     ->orWhereNull('phone_verified_at')
    //                     ->orWhereNull('correo_verified_at');
    //             })->exists();

    //         if ($opened) {
    //             $user = User::where('email', $request->input('email'))->first();

    //             $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    //             $confirmationCode = Str::random(25);

    //             $user->addresses()->delete();
    //             $data = $request->all();
    //             $data['add_user_type'] = null;
    //             $data['user_type'] = 'customer';
    //             $data['name'] = $request->input('name_user');
    //             $this->retakeUserProfile($verificationCode, $data, $confirmationCode, $request, $user);

    //             (new WhatsAppController)->sendVerificationMessage($user);

    //             Auth::login($user);

    //             return response()->json([
    //                 'state' => true,
    //                 'success' => true,
    //                 'redirect' => route('shop.view.phone.verification'),
    //                 'message' => 'Registro reactivado exitosamente'
    //             ]);
    //         }

    //         if ($request->add_user_type == 'B02') {
    //             $request->cedula_rnc = null;
    //         }

    //         if ($request->cedula_rnc != null) {
    //             $responseValidation = $this->consultarRNC($request->cedula_rnc);

    //             if (!$responseValidation['state']) {
    //                 $response = array('state' => false, 'message' => 'Error en el registro mercantíl, el estado de su RNC es: ' . $responseValidation['message'][1]['valor'] . ' Verifica tus documentos e intenta nuevamente.');
    //                 return response()->json($response);
    //             }

    //             if ($responseValidation['message'][1]['valor'] != 'ACTIVO') {
    //                 $response = array('state' => false, 'message' => 'Error en el registro mercantíl, el estado de su RNC es: ' . $responseValidation['message'][1]['valor'] . ' Verifica tus documentos e intenta nuevamente.');
    //                 return response()->json($response);
    //             }
    //         }

    //         $existingEmail = User::where('email', $request->input('email'))->first();
    //         if ($existingEmail) {
    //             $response = array('state' => false, 'message' => 'El correo electrónico ya está registrado');
    //             return response()->json($response);
    //         }

    //         $existingPhone = User::where('phone', $request->input('phone'))->first();
    //         if ($existingPhone) {
    //             $response = array('state' => false, 'message' => 'El número de celular ya está registrado');
    //             return response()->json($response);
    //         }

    //         \Log::info('Creando usuario customer...');
    //         $user = $this->createUser($request);

    //         \Log::info('Enviando mensaje WhatsApp...');
    //         (new WhatsAppController)->sendVerificationMessage($user);

    //         \Log::info('Iniciando sesión...');
    //         Auth::login($user);

    //         \Log::info('=== REGISTRO CUSTOMER COMPLETADO ===');

    //         return response()->json([
    //             'state' => true,
    //             'success' => true,
    //             'redirect' => route('shop.view.phone.verification'),
    //             'message' => 'Registro exitoso'
    //         ]);
    //     } catch (\Exception $e) {
    //         \Log::error('=== ERROR EN REGISTRO CUSTOMER ===');
    //         \Log::error('Mensaje: ' . $e->getMessage());
    //         \Log::error('Archivo: ' . $e->getFile());
    //         \Log::error('Línea: ' . $e->getLine());

    //         return response()->json([
    //             'state' => false,
    //             'success' => false,
    //             'message' => 'Error al procesar el registro. Por favor intente nuevamente.'
    //         ], 500);
    //     }
    // }


    public function registerCustomer(RegisterRequest $request)
    {
        // Revisar si tiene un proceso abierto
        $opened = User::where('email', $request->input('email'))
            ->where(function ($query) {
                $query->whereNull('email_verified_at')
                    ->orWhereNull('phone_verified_at')
                    ->orWhereNull('correo_verified_at');
            })->exists();

        if ($opened) {
            $user = User::where('email', $request->input('email'))->first();

            $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $confirmationCode = Str::random(25);

            $user->addresses()->delete();
            $data = $request->all();
            $data['add_user_type'] = null;
            $data['user_type'] = 'customer';
            $data['name'] = $request->input('name_user');
            $this->retakeUserProfile($verificationCode, $data, $confirmationCode, $request, $user);

            // Envia el codigo de verificación por WhatsApp
            (new WhatsAppController)->sendVerificationMessage($user);

            Auth::login($user);
            Log::info('User retaken: ' . $user);
            $token = $user->createToken('auth_token')->plainTextToken;
            Log::info('User token: ' . $token);
            return response()->json([
                'state' => true,
                'token' => $token,
                'user' => $user,
                'redirect' => 'https://lapieza.do/dashboard'
            ]);
        }

        if ($request->add_user_type == 'B02') {
            $request->cedula_rnc = null;
        }

        if ($request->cedula_rnc != null) {
            $responseValidation = $this->consultarRNC($request->cedula_rnc);

            if (!$responseValidation['state']) {
                $response = array('state' => false, 'message' => 'Error en el registro mercantíl, el estado de su RNC es: ' . $responseValidation['message'][1]['valor'] . ' Verifica tus documentos e intenta nuevamente.');
                return response()->json($response);
            }

            if ($responseValidation['message'][1]['valor'] != 'ACTIVO') {
                $response = array('state' => false, 'message' => 'Error en el registro mercantíl, el estado de su RNC es: ' . $responseValidation['message'][1]['valor'] . ' Verifica tus documentos e intenta nuevamente.');
                return response()->json($response);
            }
        }

        $existingEmail = User::where('email', $request->input('email'))
            //->where('user_type', 'customer')
            ->first();
        if ($existingEmail) {
            $response = array('state' => false, 'message' => 'El correo electrónico ya está registrado');
            return response()->json($response);
        }

        $existingPhone = User::where('phone', $request->input('phone'))
            //->where('user_type', 'customer')
            ->first();
        if ($existingPhone) {
            $response = array('state' => false, 'message' => 'El número de celular ya está registrado');
            return response()->json($response);
        }

        // Crear nuevo customer
        $user = $this->createUser($request);

        // Envia el codigo de verificacion de whatsapp
        (new WhatsAppController)->sendVerificationMessage($user);

        // Logea al nuevo customer
        Auth::login($user);

        $token = $user->createToken('auth_token')->plainTextToken;
        Log::info('token: ' . $token);
        return response()->json([
            'state' => true,
            'token' => $token,
            'user' => $user,
            'redirect' => route('shop.view.phone.verification')
        ]);
        // Redirecciona a la verificacion de telefono
        // //return redirect()->route('shop.view.phone.verification');
        // return response()->json([
        //     'state' => true,
        //     'redirect' => route('shop.view.phone.verification')
        // ]);
    }
    private function retakeUserProfile(string $verificationCode, array $data, string $confirmationCode, RegisterRequest|SellerRequest $request, User $user): void
    {
        $data['verification_code'] = $verificationCode;
        $data['confirmation_code'] = $confirmationCode;
        $data['email_verified_at'] = null;
        $data['phone_verified_at'] = null;
        $data['correo_verified_at'] = null;
        $data['category_translation_id'] = $request->input('categories_id') ?? null;
        $data['password'] = bcrypt($request->input('password'));
        $data['cedula'] = str_replace('-', '', $request->input('cedula_input'));
        $user->workshop()->delete();
        $user->shop()->delete();

        $user->update($data);
    }

    public function consultarRNC($rnc): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://dgii.gov.do/app/WebApps/ConsultasWeb2/ConsultasWeb/consultas/rnc.aspx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                '__VIEWSTATE' => 'qDFBUhgESRrY9yT61KTvFztiXzUHnyhpGKicmLR9cmWbo8c2wtrkWMTjH49m0cYN5TjzUS6zzPzMLsB7SfQw7Bb1QGbAOVEHfTewvRW3KjTb/+C2oI4zDwYgWbR7t3UpMFY6OsYUH/pCEs9MyTQDzPQQwUf9ig9JrxR1gzSYtqt5GF4aWzyJpHP1a/FEVp5tvVVFl18+I1lCXLp7WuyBS9xxE/8IpTb4/UpJaY/N5qM/5d8pQQATrnooFkoD2T+1598F4M8C3lrNj6nCbK2ynVqg4974wjPUgij2f6+gb18ncaORb9SMpda1Yn+KtE86zMsshoED5GK+WoiadvHK3urZCwcDRddbvYVwe+txyOFNoOQgnRx89U+y6y+UZCGzIMpUE63FrikDJtO+qp8JmhxRhrJhISHw9ZsqB/gEEcgugPGm48sAggbaui3Q5Be+TzVAUDBsN/RpKErxdM1TEh26rzUkoXAFRiaNbnUlLLxDFzERfeRcspgt3e/Wm2GJ4kO47UDya1z8+gVQfphwyAgp83NZYBnLUPbQ/8j8b2XuD+zOVwuRN/o/JJ23Pd4rwVy3OxKQWsb4rvqgS1rBUjl0/QoPelLsOYXtMeoCke6oT72QwIoG6y38xG4mRcGa/D89PE1q2ws3CwuveW7xG5j2/qPHsCE0YY707EQuIWE+/pcRlmzk0BEyxq3ynLxUPZWmxaLfKk9n2i4VyTI5l7PHH77EJ0OWgXoeqKfIt8X6WKh1F7zfNrhKZY9ZU6PK0yP2UByCjROXBmJ422SjLkZ7ct9/Cw32Nmq0melYv2zWr/+MHCIQB/2ODFdYosYJEDEwFNChkg0gKSS7yBTNWl8JSyGqAVlvyO5RIb4LJxfsNXUQTpZU+QUE2uUlVh4Morx7pf1DCY4NY8C/M6/pCDos4VXuTAUpXqO94XP76FUoxZSB7orLEZFRCyFPRD47Y/UDADUqS6KLsVQ2WdC8yi7mprxiiysi43OUBfqlWu7GWToHmM010zTVc5M+ZF3DIsznxH+r0rkrZOEOuE9IUH1fJPTpf7ECoWETdhIN2Ovfe0kWPQMCqZq6HeH7p+bB3v7xa48RfC7y/PBY26rbdUiWezmJVmZvv9b9tpaHpPHPila4UvQNZvyxhTyhtoy6SNeu22kHrH7hd4na/AcdylOTR3RZxWjroUQ1p32l1EavLht+ohccThAJk+AVMG0AKVw1F85nI8AIFKYwDe4YlAIH38531L7IR60LjCnmt4guN+a1Jv2XlAs7nOWjYW4JbiiTDvyfaEOw1Po/sGSKYJfWG90=',
                '__VIEWSTATEGENERATOR' => '4F4BAA71',
                '__EVENTVALIDATION' => '4SHa94Q3h9hTZD47TZXIcIeEcyl5B7bX+wy7fyqhv++TqW7u3GudzMg35JU5IDYmOiv5GK41SMZIKTFHEwsHZAOk0qvvj8Py/bscxaEwUYrwy5o5I4vhyYo/ssRPMcyLqhLr9D0AFOeFR5ZqGY25uw4xZxIf+d1RLpyelX2JMHmVHlJROnRXaKw6QfxOIED44QGBf025Qy/FehV2RmD9hVcjgZsdK4r/Y2b9cfAXuP0gmeG1LFV+XBTiVeClzeBKzs50FELFY+RPvBG2ok4W5/MLl60=',
                'ctl00$cphMain$txtRNCCedula' => $rnc,
                'ctl00$cphMain$txtRazonSocial' => '',
                'ctl00$cphMain$hidActiveTab' => '',
                'ctl00$cphMain$Button1' => '',
                'ctl00$cphMain$btnBuscarPorRNC' => ''
            ),
            CURLOPT_HTTPHEADER => array(
                'Cookie: NSC_EHJJ_BQQ_TTM_MCWT=ffffffffc3a0e02045525d5f4f58455e445a4a423660'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_VERBOSE => true
        ));

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($httpCode !== 200) {
            return array('state' => false, 'message' => 'Error al realizar la petición. Código de estado HTTP: ' . $httpCode);
        }

        $dom = new DOMDocument;
        @$dom->loadHTML($response);

        $tabla = $dom->getElementById('cphMain_dvDatosContribuyentes');

        if ($tabla) {
            $respuesta = [];
            $filas = $tabla->getElementsByTagName('tr');
            $filas_seleccionadas = [1, 5];
            foreach ($filas_seleccionadas as $indice) {
                if (isset($filas[$indice])) {
                    $fila = $filas[$indice];
                    $celdas = $fila->getElementsByTagName('td');

                    $respuesta[] = [
                        'campo' => trim($celdas[0]->textContent),
                        'valor' => trim($celdas[1]->textContent)
                    ];
                }
            }

            if (!$respuesta) {
                return array(
                    'state' => false,
                    'message' => [
                        0 => [
                            'valor' => ''
                        ],
                        1 => [
                            'valor' => 'El RNC/Cédula consultado no se encuentra inscrito como Contribuyente'
                        ]
                    ]
                );
            } else {
                return array('state' => true, 'message' => $respuesta);
            }
        } else {
            return array('state' => false, 'message' => 'Error, no se encontraron datos. Intente nuevamente.');
        }
    }

    private function createUser(Request $request, string $type = 'customer', bool $is_workshop = false): User
    {
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $confirmationCode = Str::random(25);

        $user = User::make([
            'name' => $request->name_user,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'user_type' => $type,
            'telefono_tienda' => $request->telefono_tienda ?? $request->phone,
            'confirmation_code' => $confirmationCode,
            'verification_code' => $verificationCode,
            'cedula' => $request->cedula_rnc ?? '',
            'type_ncf' => $request->add_user_type ?? '',
            'address' => $request->address ?? '',
            'city' => $request->city ?? '',
            'postal_code' => $request->postal_code ?? '',
            'country' => $request->country ?? '',
        ]);

        if ($type == 'seller' && !$is_workshop) {
            $user->category_translation_id = $request->input('categories_id');
            $user->cedula = str_replace('-', '', $request->input('cedula_input'));
        }

        if ($type == 'seller' && $is_workshop) {
            $user->add_user_type = 'workshop';
        }

        $user->save();

        return $user;
    }

    public function consultarRNCRute(Request $request)
    {
        $rnc = $request->input('rnc');

        if (empty($rnc)) {
            return response()->json([
                'state' => false,
                'message' => 'El campo RNC/Cédula es requerido.'
            ]);
        }

        $response = $this->consultarRNC($rnc);

        return response()->json($response);
    }

    public function registerSeller(SellerRequest $request)
    {
        \Log::info('=== INICIO REGISTRO SELLER ===');
        \Log::info('Datos recibidos:', $request->all());

        try {
            $imageFileDi = $request->file('cedula_photo');

            $docInput = $request->input('cedula_input');
            $cleanedInputDoc = str_replace(['-', ' '], '', $docInput);

            if (!$request->has('is_physical_person') && empty($request->input('telefono_tienda'))) {
                return response()->json(['state' => false, 'message' => 'El teléfono de tienda es requerido cuando no eres una persona física.']);
            }

            $userExists = User::where('cedula', $docInput)->exists();

            if ($userExists) {
                $response = array('state' => false, 'message' => 'La cédula ya está registrada');
                return response()->json($response);
            }

            if (!$request->has('is_physical_person')) {
                if (!$request->hasFile('registro_mercantil')) {
                    $response = array('state' => false, 'message' => 'Debes adjuntar una imagen/pdf de tu registro mercantil');
                    return response()->json($response);
                }

                $imageFile = $request->file('registro_mercantil');
                $rncInput = $request->input('rnc_input');
                $cleanedInput = str_replace(['-', ' '], '', $rncInput);

                \Log::info('Validando RNC con DGII...');
                $responseValidation = $this->consultarRNC($cleanedInput);
                \Log::info('Respuesta validación RNC:', ['response' => $responseValidation]);

                if (!$responseValidation['state']) {
                    $response = array('state' => false, 'message' => 'Error en el registro mercantíl, el estado de su RNC es: ' . $responseValidation['message'][1]['valor'] . ' Verifica tus documentos e intenta nuevamente.');
                    return response()->json($response);
                }

                if ($responseValidation['message'][1]['valor'] != 'ACTIVO') {
                    $response = array('state' => false, 'message' => 'Error en el registro mercantíl, el estado de su RNC es: ' . $responseValidation['message'][1]['valor'] . ' Verifica tus documentos e intenta nuevamente.');
                    return response()->json($response);
                }
            } else {
                $responseValidation = $this->consultarRNC($cleanedInputDoc);

                if (!$responseValidation['state']) {
                    $response = array('state' => false, 'message' => 'Error! Su Cédula no esta registrada como Persona Física.');
                    return response()->json($response);
                }

                if ($responseValidation['message'][1]['valor'] != 'ACTIVO') {
                    $response = array('state' => false, 'message' => 'Error! Su Registro como Persona Física es: ' . $responseValidation['message'][1]['valor'] . ' Verifica tus documentos e intenta nuevamente.');
                    return response()->json($response);
                }
            }

            $existingEmail = User::where('email', $request->input('email'))->first();
            if ($existingEmail) {
                $response = array('state' => false, 'message' => 'El correo electrónico ya está registrado');
                return response()->json($response);
            }

            $existingPhone = User::where('phone', $request->input('phone'))->first();
            if ($existingPhone) {
                $response = array('state' => false, 'message' => 'El número de celular ya está registrado');
                return response()->json($response);
            }

            if ($this->restartSeller($request)) {
                return response()->json([
                    'state' => true,
                    'success' => true,
                    'redirect' => route('seller.dashboard'),
                    'message' => 'Registro reactivado exitosamente'
                ]);
            }

            \Log::info('Creando usuario seller...');
            $user = $this->createUser($request, 'seller');
            \Log::info('Usuario creado con ID: ' . $user->id);

            $user->update([
                'cedula_id' => FileUploadController::uploadImage($request, 'cedula_photo', $user)
            ]);

            \Log::info('Creando tienda...');
            $this->createStore($request, $user);

            \Log::info('Creando dirección...');
            $this->createAddress($request, $user);

            \Log::info('Enviando mensaje WhatsApp...');
            (new WhatsAppController)->sendVerificationMessage($user);

            \Log::info('Iniciando sesión...');
            Auth::login($user);

            \Log::info('=== REGISTRO COMPLETADO EXITOSAMENTE ===');

            return response()->json([
                'state' => true,
                'success' => true,
                'redirect' => route('shop.view.phone.verification'),
                'message' => 'Registro exitoso'
            ]);
        } catch (\Exception $e) {
            \Log::error('=== ERROR EN REGISTRO SELLER ===');
            \Log::error('Mensaje: ' . $e->getMessage());
            \Log::error('Archivo: ' . $e->getFile());
            \Log::error('Línea: ' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'state' => false,
                'success' => false,
                'message' => 'Error al procesar el registro. Por favor intente nuevamente.'
            ], 500);
        }
    }

    private function restartSeller(SellerRequest $request): bool
    {
        $opened = User::where('email', $request->input('email'))
            ->where(function ($query) {
                $query->whereNull('email_verified_at')
                    ->orWhereNull('phone_verified_at')
                    ->orWhereNull('correo_verified_at');
            })->exists();

        if ($opened) {
            $user = User::where('email', $request->input('email'))->first();

            $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $confirmationCode = Str::random(25);

            $user->addresses()->delete();
            $data = $request->all();
            $data['add_user_type'] = null;
            $data['user_type'] = 'seller';
            $data['name'] = $request->input('name_user');
            $this->retakeUserProfile($verificationCode, $data, $confirmationCode, $request, $user);

            FileUploadController::deleteImage($user->cedula_id);
            if ($user->rnc_id) FileUploadController::deleteImage($user->rnc_id);

            $user->update([
                'cedula_id' => FileUploadController::uploadImage($request, 'cedula_photo', $user),
            ]);

            $this->createStore($request, $user);
            $this->createAddress($request, $user);
            (new WhatsAppController)->sendVerificationMessage($user);
            Auth::login($user);

            return true;
        }

        return false;
    }

    private function createStore(Request $request, User $user): void
    {
        $paquete = SellerPackage::where('id', 4)->first();
        $date = new DateTime();
        $date->modify('+5 years');
        $formattedDate = $date->format('Y-m-d');

        $logoId = FileUploadController::uploadStaticImage(public_path('assets/img/placeholder.jpg'), $user);

        $rncInput = $request->input('rnc_input') ?? $request->input('cedula_input');
        $rnc = str_replace('-', '', $rncInput);

        Shop::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'rnc' => $rnc,
            'rnc_id' => FileUploadController::uploadImage($request, 'registro_mercantil', $user),
            'seller_package_id' => $paquete['id'],
            'product_upload_limit' => $paquete['product_upload_limit'],
            'package_invalid_at' => $formattedDate,
            'phone' => $user->telefono_tienda ?? $user->phone,
            'address' => $user->address,
            'country' => $user->country,
            'state' => $user->state,
            'city' => $user->city,
            'postalCode' => $request->postalCode,
            'delivery_pickup_latitude' => $request->latitude,
            'delivery_pickup_longitude' => $request->longitude,
            'meta_title' => $request->name,
            'meta_description' => $request->name,
            'logo' => $logoId
        ]);
    }

    private function createAddress(Request $request, User $user): void
    {
        Address::create([
            'user_id' => $user->id,
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'state' => $request->state,
            'postalCode' => $request->postalCode,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'set_default' => 1
        ]);
    }

    public function registerWorkshop(SellerRequest $request)
    {
        $request->validated();

        $opened = User::where('email', $request->input('email'))
            ->where(function ($query) {
                $query->whereNull('email_verified_at')
                    ->orWhereNull('phone_verified_at')
                    ->orWhereNull('correo_verified_at');
            })->exists();

        if ($opened) {
            $user = User::where('email', $request->input('email'))->first();

            $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $confirmationCode = Str::random(25);

            $user->addresses()->delete();
            $data = $request->all();
            $data['user_type'] = 'seller';
            $data['add_user_type'] = 'workshop';
            $data['name'] = $request->input('name_user');
            $this->retakeUserProfile($verificationCode, $data, $confirmationCode, $request, $user);

            $this->createWorkshop($request, $user);
            $this->createStore($request, $user);
            $this->createAddress($request, $user);
            (new WhatsAppController)->sendVerificationMessage($user);
            Auth::login($user);

            return redirect()->route('workshop.dashboard');
        }

        $user = $this->createUser($request, 'seller', true);
        $this->createWorkshop($request, $user);
        $this->createStore($request, $user);
        $this->createAddress($request, $user);
        (new WhatsAppController)->sendVerificationMessage($user);
        Auth::login($user);

        return redirect()->route('shop.view.phone.verification');
    }

    private function createWorkshop(Request $request, User $user)
    {
        $workshop = Workshop::create([
            'name' => $request->input('name'),
            'user_id' => $user->id,
            'delivery_pickup_latitude' => $request->latitude,
            'delivery_pickup_longitude' => $request->longitude
        ]);

        $workshop->categories()->sync($request->categories_id);
    }

    private function validateRegistroMercantil($imageFile): array
    {
        try {
            $imagePath = $imageFile->store('temp', 'public');
            $client = new Client();
            $response = $client->post(env('OCR_VALIDATION_API_URL'), [
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen(storage_path('app/public/' . $imagePath), 'r'),
                        'filename' => $imageFile->getClientOriginalName()
                    ],
                ],
            ]);

            $responseBody = json_decode($response->getBody(), true);
            Storage::disk('public')->delete($imagePath);

            if (isset($responseBody['success']) && $responseBody['success']) {
                return [
                    'success' => true,
                    'message' => $responseBody['message'] ?? 'OCR validado con éxito'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $responseBody['message'] ?? 'Fallo en la validación OCR'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error en la validación del registro mercantil: ' . $e->getMessage()
            ];
        }
    }

    public function buyerView()
    {
        return view('frontend.registro-comercio.views.buyer.index');
    }

    private function validateCedula($imageFile): array
    {
        try {
            $imagePath = $imageFile->store('temp', 'public');
            $client = new Client();
            $response = $client->post(env('SCRAPPER_INFO_API_URL'), [
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen(storage_path('app/public/' . $imagePath), 'r'),
                        'filename' => $imageFile->getClientOriginalName()
                    ],
                ],
            ]);

            $responseBody = json_decode($response->getBody(), true);
            Storage::disk('public')->delete($imagePath);

            return $responseBody;
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error en la validación del documento de identidad: ' . $e->getMessage()
            ];
        }
    }
}
