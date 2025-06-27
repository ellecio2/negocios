<?php

namespace App\Rules\Auth;

use Illuminate\Contracts\Validation\Rule;

class EmailValidationRule implements Rule {
    public function passes($attribute, $value) {
        $domain = explode('@', $value)[1];
        return filter_var($value, FILTER_VALIDATE_EMAIL) && checkdnsrr($domain, 'MX');
    }

    public function message() {
        return trans('No has ingresado un correo electronico valido');
    }
}
