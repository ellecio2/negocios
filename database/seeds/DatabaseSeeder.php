<?php

namespace Database\seeds;

use Database\Seeders\TransporteBlancoCategoriaSeeder;
use Database\Seeders\TransporteBlancoZonaSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(){

        $this->command->warn('Estas a punto de restaurar la base de datos');
        $response = $this->command->ask('¿Estas seguro de continuar?', false);
        if($response == false){
            $this->command->info('Operación cancelada');
            return;
        }else{
            /*
             * A futuro se ejecuta este comando para restaurar la base de datos y rellenarla
             * desde 0
             * */

            //$this->command->call('migrate:fresh');

            $this->call([
                UserTestSeeder::class,
//                TransporteBlancoCategoriaSeeder::class,
//                TransporteBlancoZonaSeeder::class,
            ]);

            $this->command->info('Base de datos restaurada');
        }
    }
}
