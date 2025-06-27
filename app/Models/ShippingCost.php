<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Casts\Attribute;



class ShippingCost extends Model {



    protected $table = 'shipping_costs';



    protected $primaryKey = 'id';



    protected $fillable = [

        'starter_price',

        'ending_price',

        'pedidosya_secure_difference',

        'shipping_id',

        'type',

        'shipping_company',

        'require_transfer',

        'expiration_date',

        'delivery_offer_id',

        'estimated_date',

        'cart_id',
        'order_id'

    ];



    protected $attributes = [

        'require_transfer' => false,

        'type' => 'estimated',

        'shipping_company' => 'PEDIDOS YA',

        'starter_price' => 0.00,

        'ending_price' => 0.00

    ];



    public $timestamps = false;



    protected $casts = [

        'require_transfer' => 'boolean',

        'expiration_date' => 'datetime',

        'starter_price' => 'float',

        'ending_price'

    ];



    public function cart() : BelongsTo {

        return $this->belongsTo(Cart::class, 'cart_id', 'id');

    }



    public function order() : BelongsTo {

        return $this->belongsTo(Order::class, 'order_id', 'id');

    }



    protected function shippingCompany(): Attribute {

        return Attribute::make(get: fn($value) => strtoupper($value), set: fn($value) => strtoupper($value));

    }



    protected function type(): Attribute {

        return Attribute::make(get: fn($value) => strtoupper($value), set: fn($value) => strtoupper($value));

    }

}

