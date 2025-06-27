<?php

namespace Database\Seeders;

use App\Models\TransporteBlancoCategoria;
use Illuminate\Database\Seeder;

class TransporteBlancoCategoriaSeeder extends Seeder
{
    private $zonas = [
        'CIBAO CORTO' => 260.00,
        'SUR CORTO' => 260.00,
        'ESTE CORTO' => 260.00,
        'CIBAO LARGO' => 280.00,
        'SUR LARGO' => 280.00,
        'ESTE LARGO' => 280.00,
        'ESPECIALES' => 300.00,
        'SANTO DOMINGO' => 240.00,
    ];
    public function run(): void
    {
        foreach($this->zonas as $key => $zona){
            TransporteBlancoCategoria::factory()->create([
                'nombre' => $zona,
                'precio' => $key
            ]);
        }
    }
}
