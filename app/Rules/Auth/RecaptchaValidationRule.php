<?php

namespace App\Rules\Auth;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;
use ReCaptcha\ReCaptcha;

class RecaptchaValidationRule implements Rule {
    public function passes($attribute, $value): bool {
        $recaptcha = new Recaptcha(config('app.recaptcha_secret'));
        $response = $recaptcha->verify($value);
        return $response->isSuccess();
    }

    public function message(): string {
        return 'El recaptcha es incorrecto.';
    }
}
