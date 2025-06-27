<?php

namespace App\Http\Resources\V2\Attributes;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeValuesResource extends JsonResource {
    public function toArray($request) {
        return [
            'value' => $this->value,
        ];
    }
}
