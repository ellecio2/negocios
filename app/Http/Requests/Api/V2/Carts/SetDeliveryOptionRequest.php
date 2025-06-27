<?php

namespace App\Http\Requests\Api\V2\Carts;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SetDeliveryOptionRequest extends FormRequest {
    public function rules(): array {
        return [
            'type' => 'required|in:carrier,pickup point',
            'shippingCompany' => 'nullable|string',
            'shippingCost' => 'nullable|numeric'
        ];
    }

    public function authorize(): bool {
        return true;
    }

    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()->all()
        ], 403));
    }

    public function messages(): array {
        return [
            'type.required' => 'NO_DELIVERY_TYPE_PROVIDED',
            'type.in' => 'THE_DELIVERY_TYPE_IS_NOT_CORRECT'
        ];
    }
}
