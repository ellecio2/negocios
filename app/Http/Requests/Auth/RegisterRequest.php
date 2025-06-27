<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Rules\Auth\EmailValidationRule;
use App\Rules\Auth\RecaptchaValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use ReCaptcha\ReCaptcha;

class RegisterRequest extends FormRequest {
    public function rules(): array {
        return self::commonRegisterRules();
    }
    public function commonRegisterRules() : array{
        return [
            'name_user' => 'required',
            /*'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->where(function ($query) {
                    return $query->whereNotNull('email_verified_at')
                        ->whereNotNull('phone_verified_at')
                        ->whereNotNull('correo_verified_at');
                }),
                'regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+.(com|do|net|com.do)$/i',
                new EmailValidationRule()
            ],*/
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => 'required',
            'g-recaptcha-response' => [
                'required',
                new RecaptchaValidationRule()
            ],
        ];
    }

    public function authorize(): bool {
        return true;
    }

    public function failedValidation(Validator $validator) {
        //return back()->withErrors($validator)->withInput();

        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422));
        }

        return back()->withErrors($validator)->withInput();
    }

    /*public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }*/
}
