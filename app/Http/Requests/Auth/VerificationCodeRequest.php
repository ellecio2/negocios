<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class VerificationCodeRequest extends FormRequest {
    public function rules(): array {
        return [
            'verification_code' => 'required|numeric|digits:6'
        ];
    }

    public function authorize(): bool {
        return true;
    }

    public function messages(): array {
        return [
            'verification_code.required' => 'El codigo de verificación es requerido.',
            'verification_code.numeric' => 'El código de verififcación es invalido.',
            'verification_code.digits' => 'El código de verififcación es invalido.',
        ];
    }

    public function failedValidation(Validator $validator) {
        return response()->json([
            'result' => false,
            'message' => translate('Code does not match, you can request for resending the code'),
            'errors' => $validator->errors()
        ], 422);
    }
}
