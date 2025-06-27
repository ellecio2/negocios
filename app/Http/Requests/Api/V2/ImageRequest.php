<?php

namespace App\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest {
    public function rules(): array {
        return [
            'image' => 'required|image'
        ];
    }

    public function authorize(): bool {
        return true;
    }
}
