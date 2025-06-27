<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class SellerRequest extends FormRequest {
    public function rules(): array {

        $rules = (new RegisterRequest)->commonRegisterRules();

        $rules = array_merge($rules, [
            'name' => ['required'],
            'address' => ['required'],
            'categories_id' => ['required'],
            'cedula_input' => ['required'],
            'cedula_photo' => ['required']
        ]);

        return $rules;
    }

    public function authorize(): bool {
        return true;
    }

    public function messages() {
        $messages = (new RegisterRequest)->messages();

        $messages = array_merge($messages, [
            'name.required' => ':attribute es requerido.',
            'address.required' => ':attribute es requerido.',
            'categories_id.required' => 'Es necesario elegir una categoría',
        ]);

        return $messages;
    }

    /*public function failedValidation(Validator $validator) {
        return back()->withErrors($validator)->withInput();
    }*/

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
