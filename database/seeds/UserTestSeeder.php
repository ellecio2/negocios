<?php

namespace Database\seeds;

use App\Models\CategoryTranslation;
use App\Models\UserTest;
use Illuminate\Database\Seeder;

class UserTestSeeder extends Seeder
{
    public function run(): void{
        $categoryTranslations = CategoryTranslation::all();

        /*
         * Se crea por defecto el customer kelvyn declarado en el factory
         * */
        UserTest::factory()->kelvyn()->create();

        do {
            $answer = $this->command->ask('¿Cuantos usuarios desea crear?', 10);

            if ($answer < 0) {
                $this->command->error('El número de usuarios debe ser mayor a 0');
            }else{
                $this->command->info("Creando {$answer} usuarios");

                UserTest::factory($answer)->make()->each(function ($user) use ($categoryTranslations) {
                    $user->category_translation_id = $categoryTranslations->random()->id;
                    $user->save();
                });
            }
        }while ($answer < 0);
    }
}
