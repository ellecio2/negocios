<?php

namespace Database\Factories;

use App\Models\TransporteBlancoCategoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransporteBlancoCategoriaFactory extends Factory
{
    protected $model = TransporteBlancoCategoria::class;
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->city(),
            'precio' => $this->faker->randomFloat(2, 0, 1000)
        ];
    }
}
