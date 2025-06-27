<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class BusinessWorkingHours extends Model

{

    use HasFactory;



    protected $fillable = [

        'shop_id',

        'dia_semana',

        'hora_inicio',

        'hora_fin',

        'laborable',

    ];



    public function user()

    {

        return $this->belongsTo(User::class);

    }

}

