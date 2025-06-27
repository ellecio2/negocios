<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id;
 * @property int $combined_order_id
 * @property int $user_id
 * @property int $guest_id
 * @property int $seller_id
 * @property string $shipping_address
 * @property string $additional_info
 * @property string $shipping_type
 * @property string $order_from
 * @property int $pickup_point_id
 * @property int $carrier_id
 * @property string $delivery_status
 * @property string $payment_type
 * @property int $manual_payment
 * @property string $manual_payment_data
 * @property string $payment_status
 * @property string $payment_details
 * @property float $grand_total
 * @property float $coupon_discount
 * @property string $code
 * @property string $tracking_code
 * @property string $date
 * @property bool $viewed
 * @property bool $delivery_viewed
 * @property bool $payment_status_viewed
 * @property bool $comission_calculated
 * @property string $deliver_date
 * @property bool $workshop_request
 * @property int $shop_id
 * @property int $category_translation_id
 * @property int $assign_delivery_boy
 * @property bool $cancel_request
 * @property string $delivery_history_date
 * @property int $shipping_cost_id
 * @property int $ncf_id
 */
class Order extends Model
{

    protected $fillable = [
        'combined_order_id',
        'user_id',
        'guest_id',
        'seller_id',
        'shipping_address',
        'additional_info',
        'shipping_type',
        'order_from',
        'pickup_point_id',
        'carrier_id',
        'delivery_status',
        'payment_type',
        'manual_payment',
        'manual_payment_data',
        'payment_status',
        'payment_details',
        'grand_total',
        'coupon_discount',
        'code',
        'tracking_code',
        'date',
        'viewed',
        'delivery_viewed',
        'payment_status_viewed',
        'comission_calculated',
        'deliver_date',
        'workshop_request',
        'shop_id',
        'category_translation_id',
        'assign_delivery_boy',
        'cancel_request',
        'delivery_history_date',
        'shipping_cost_id',
        'ncf_id',
        'nro_ncf'
    ];

    protected $attributes = [
        'delivery_viewed' => '0',
        'payment_status_viewed' => '0'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->code = date('Ymd-his') . rand(10, 99);
            $order->date = Carbon::now()->getTimestamp();
        });
    }

    public function shippingCost()
    {
        return $this->hasOne(ShippingCost::class, 'id', 'shipping_cost_id');
        //return $this->hasOne(ShippingCost::class, 'shipping_id', 'shipping_cost_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function refund_requests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class, 'user_id', 'seller_id');
    }

    public function pickup_point()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }

    public function club_point()
    {
        return $this->hasMany(ClubPoint::class);
    }

    public function delivery_boy()
    {
        return $this->belongsTo(User::class, 'assign_delivery_boy', 'id');
    }

    public function proxy_cart_reference_id()
    {
        return $this->hasMany(ProxyPayment::class)->select('reference_id');
    }

    public function combinedOrder(): BelongsTo
    {
        return $this->belongsTo(CombinedOrder::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'id', 'order_id');
    }

    public function getFormatedShippingTypeAttribute()
    {
        return ucfirst(strtolower($this->shipping_type));
    }
    public function order_details()
    {
        return $this->hasMany(\App\Models\OrderDetail::class);
    }
}
