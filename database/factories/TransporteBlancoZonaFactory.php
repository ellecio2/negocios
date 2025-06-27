<?php

namespace Database\Factories;

use App\Models\TransporteBlancoZona;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransporteBlancoZonaFactory extends Factory
{
    protected $model = TransporteBlancoZona::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name,
            'latitud' => $this->faker->latitude,
            'longitud' => $this->faker->longitude,
        ];
    }
}
