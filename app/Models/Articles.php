<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;



class Articles extends Model

{
    protected $fillable = [
        'user_id',
        'category_id',
        'year',
        'make',
        'model',
        'modelo',
        'trim',
        'engine',
        'image',
        'chasis_serial'
    ];

    public function user(){

    	return $this->belongsTo(User::class);

    }

}

