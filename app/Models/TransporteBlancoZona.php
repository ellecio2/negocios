<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransporteBlancoZona extends Model
{
    use HasFactory;

    protected $table = 'transporte_blanco_zonas';

    protected $fillable = [
        'nombre',
        'latitud',
        'longitud',
        'transporte_blanco_categoria_id'
    ];

    public $timestamps = false;

    public function categoria(){
        return $this->belongsTo(TransporteBlancoCategoria::class, 'transporte_blanco_categoria_id', 'id');
    }

    public function pueblos(){
        return $this->hasMany(TransporteBlancoPueblo::class);
    }
}
