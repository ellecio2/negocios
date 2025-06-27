<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransporteBlancoPueblo extends Model
{
    use HasFactory;

    protected $table = 'transporte_blanco_pueblos';

    protected $fillable = [
        'nombre',
        'latitud',
        'longitud',
        'dias_disponibles',
        'transporte_blanco_zona_id'
    ];

    public $timestamps = false;

    public function zona(){
        return $this->belongsTo(TransporteBlancoZona::class, 'transporte_blanco_zona_id', 'id');
    }
}
