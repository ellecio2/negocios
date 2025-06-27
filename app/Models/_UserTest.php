<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class UserTest extends Model

{

    /*

     * Es importante usar el HasFactory para poder crear datos de prueba

    */

    use HasFactory;



    /*

     * El modelo se llama UserTest Laravel por defecto busca la tabla users_tests

     * Solo en caso de que la tabla sea diferente hay que definir la tabla

    */

    protected $table = 'users_test';



    protected $fillable = [

        'referred_by',

        'provider',

        'provider_id',

        'refresh_token',

        'access_token',

        'user_type',

        'name',

        'email',

        'email_verified_at',

        'phone_verified_at',

        'correo_verified_at',

        'confirmation_code',

        'verification_code',

        'new_email_verificiation_code',

        'password',

        'remember_token',

        'device_token',

        'avatar',

        'avatar_original',

        'address',

        'country',

        'state',

        'city',

        'postal_code',

        'phone',

        'balance',

        'banned',

        'referral_code',

        'customer_package_id',

        'remaining_uploads',

        'category_translation_id',

        'add_user_type',

        'articles'

    ];



    /*

     * Escondemos información vulnerable para que no se muestre en las consultas

     * si esta solo que se oculta para evitar vulnerabilidades

     * */

    protected $hidden = [

        'password',

    ];



    /*

     * Se puede definir el tipo de dato que se va a guardar en la base de datos

     * la ventaja es que cuando mandamos a llamar a este dato lo convierte en una fecha

     * y podemos usar los metodos de Carbon sin necesidad de tener que hacer conversiones

     * de string a date con codigo

     *

     * No es necesario definir created_at y update_at ya que laravel lo hace por defecto

     * */

    protected $casts = [

        'email_verified_at' => 'datetime',

        'phone_verified_at' => 'datetime',

        'correo_verified_at' => 'datetime',

    ];



    /*

     * Definimos los valores por defecto para que cuando se cree un nuevo modelo ya lleve estos datos

     * llenos, esto esta ya implementado en la BD pero es buena practica implementarlo tambien a la

     * hora de crear el modelo

     * */

    protected $attributes = [

        'user_type' => 'customer',

        'banned' => 0,

        'balance' => 0.00,

        'remaining_uploads' => 0,

    ];



    /*

     * Definimos las relaciones que tiene el modelo

     * esto nos ayuda a que cuando hagamos una consulta

     * podamos usar $user->translation->name

     * y asi no hacer consultas extras

     * */

    public function translation(){

        return $this->belongsTo(CategoryTranslation::class);

    }



    public function referredBy(){

        return $this->belongsTo(User::class, 'referred_by', 'id');

    }







}

