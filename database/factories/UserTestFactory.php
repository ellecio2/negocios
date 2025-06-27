<?php

namespace Database\Factories;

use App\Models\UserTest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserTestFactory extends Factory
{
    protected $model = UserTest::class;

    public function definition(): array
    {
        return [
            'referred_by' => $this->faker->randomNumber(),
            'provider' => $this->faker->word,
            'provider_id' => $this->faker->word,
            'refresh_token' => $this->faker->word,
            'access_token' => $this->faker->word,
            'user_type' => $this->faker->randomElement(['customer', 'seller', 'admin', 'repair', 'delivery_boy', 'staff', 'workshop']),
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'email_verified_at' => Carbon::now(),
            'phone_verified_at' => Carbon::now(),
            'correo_verified_at' => Carbon::now(),
            'confirmation_code' => $this->faker->word,
            'verification_code' => $this->faker->word,
            'new_email_verificiation_code' => $this->faker->word,
            'password' => bcrypt('123456'),
            'remember_token' => $this->faker->word,
            'device_token' => $this->faker->word,
            'avatar' => $this->faker->word,
            'avatar_original' => $this->faker->word,
            'address' => $this->faker->streetAddress,
            'country' => $this->faker->country(),
            'state' => $this->faker->word,
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->numerify('#####'),
            'phone' => $this->faker->phoneNumber(),
            'balance' => $this->faker->randomFloat(2),
            'banned' => $this->faker->boolean,
            'referral_code' => $this->faker->word,
            'remaining_uploads' => $this->faker->randomNumber(),
            'add_user_type' => $this->faker->randomElement(['customer', 'seller', 'admin', 'repair', 'delivery_boy', 'staff', 'workshop']),
        ];
    }

    public function kelvyn(){
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Kelvyn Quiroz',
                'email' => 'elleciomusic@gmail.com',
                'email_verified_at' => '2023-08-30 17:19:27',
                'password' => bcrypt('Sampler02'),
                'user_type' => 'customer',
                'phone' => '8095369790',
                'avatar_original' => '74',
                'device_token' => 'c7FhQfmeSwm9sMb9HiM0sn:APA91bHA7_ZbOYP-EbFbpvDfpeBR0bzih5H_K_4FmUu5wRt0dzX94znRApXJ3AfeLqsuo7pQ2MjLxX04HQ_SPKCqq3hh9D-Hu2R8fLUTsTWNQamwEkEtMB4SsFb3U_AJZ0bDrryCWXPn',
                'remember_token' => 'FM9LYVRB78Pd5Fe6Q4A576rTLbSFhI4sYNqGV4zYzOwUTWt1YwpbboPm3XP4'
            ];
        });
    }
}
