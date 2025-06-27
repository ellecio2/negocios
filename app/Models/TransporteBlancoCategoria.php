<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransporteBlancoCategoria extends Model
{
    use HasFactory;

    protected $table = 'transporte_blanco_categorias';

    protected $fillable = [
        'nombre',
        'precio'
    ];

    public $timestamps = false;

    public function zonas(){
        return $this->hasMany(TransporteBlancoZona::class);
    }
}
