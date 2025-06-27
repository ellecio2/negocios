<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class BusinessDateNonWorking extends Model

{

    use HasFactory;



    protected $fillable = [

        'shop_id',

        'fecha_no_laborable',

        'nota',

    ];



    public function user()

    {

        return $this->belongsTo(User::class);

    }

}

