<?php

namespace App\Models;

use App\Notifications\EmailVerificationNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $phone
 * @property string $email
 * @property float $balance
 * @property string $name
 * @property string $verification_code
 * @property Shop $shop
 */
class User extends Authenticatable implements MustVerifyEmail {
    use Notifiable, HasApiTokens, HasRoles, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'city',
        'postal_code',
        'phone',
        'country',
        'provider_id',
        'email_verified_at',
        'phone_verified_at',
        'correo_verified_at',
        'verification_code',
        'login_token',
        'add_user_type',
        'confirmation_code',
        'user_type',
        'articles',
        'referred_by',
        'verification_type',
        'verification_document_id',
        'cedula',
        'cedula_id',
        'type_ncf'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendEmailVerificationNotification() : void {
        $this->notify(new EmailVerificationNotification());
    }

    public function wishlists() : HasMany {
        return $this->hasMany(Wishlist::class);
    }

    public function customer() : HasOne {
        return $this->hasOne(Customer::class);
    }

    public function affiliate_user() : HasOne {
        return $this->hasOne(AffiliateUser::class);
    }

    public function affiliate_withdraw_request() : HasMany {
        return $this->hasMany(AffiliateWithdrawRequest::class);
    }

    public function products() : HasMany {
        return $this->hasMany(Product::class);
    }

    public function shop() : HasOne {
        return $this->hasOne(Shop::class);
    }

    public function seller() : HasOne {
        return $this->hasOne(Seller::class);
    }

    public function staff() : HasOne {
        return $this->hasOne(Staff::class);
    }

    public function orders() : HasMany {
        return $this->hasMany(Order::class);
    }

    public function seller_orders() : HasMany {
        return $this->hasMany(Order::class, "seller_id");
    }

    public function seller_sales() : HasMany {
        return $this->hasMany(OrderDetail::class, "seller_id");
    }

    public function wallets() : HasMany {
        return $this->hasMany(Wallet::class)->orderBy('created_at', 'desc');
    }

    public function articles() : HasMany {
        return $this->hasMany(Articles::class);
    }

    public function club_point() : HasOne {
        return $this->hasOne(ClubPoint::class);
    }

    public function customer_package() : BelongsTo {
        return $this->belongsTo(CustomerPackage::class);
    }

    public function customer_package_payments() : HasMany {
        return $this->hasMany(CustomerPackagePayment::class);
    }

    public function customer_products() : HasMany {
        return $this->hasMany(CustomerProduct::class);
    }

    public function seller_package_payments() : HasMany {
        return $this->hasMany(SellerPackagePayment::class);
    }

    public function carts() : HasMany {
        return $this->hasMany(Cart::class);
    }

    public function reviews() : HasMany {
        return $this->hasMany(Review::class);
    }

    public function addresses() : HasMany {
        return $this->hasMany(Address::class);
    }

    public function affiliate_log() : HasMany {
        return $this->hasMany(AffiliateLog::class);
    }

    public function product_bids() : HasMany {
        return $this->hasMany(AuctionProductBid::class);
    }

    public function product_queries() : HasMany {
        return $this->hasMany(ProductQuery::class, 'customer_id');
    }

    public function uploads() : HasMany {
        return $this->hasMany(Upload::class);
    }

    public function conversations() : BelongsToMany {
        return $this->belongsToMany(WhatsappOpenedConversation::class, 'users_has_conversations', 'user_id', 'conversation_id')
            ->withPivot(['workshop_proposal_id', 'workshop_id', 'process']);
    }

    public function workshop() : HasOne {
        return $this->hasOne(Workshop::class);
    }

    public function cedulaPicture(){
        return Upload::find($this->cedula_id)->file_name;
    }
}
