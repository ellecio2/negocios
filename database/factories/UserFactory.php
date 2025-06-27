<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory {
    protected $model = User::class;

    public function definition(): array {
        return [
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'balance' => $this->faker->randomFloat(),
            'name' => $this->faker->name(),
            'verification_code' => $this->faker->word(),
        ];
    }
}
