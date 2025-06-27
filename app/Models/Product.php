<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $digital
 * @property string $name
 * @property int $earn_point
 * @property int $num_of_sale
 * @property float $unit_price
 */
class Product extends Model {
    protected $fillable = [
        "name",
        "added_by",
        "user_id",
        "category_id",
        "brand_id",
        "photos",
        "thumbnail_img",
        "video_provider",
        "video_link",
        "tags",
        "description",
        "unit_price",
        "purchase_price",
        "variant_product",
        "attributes",
        "choice_options",
        "colors",
        "variations",
        "todays_deal",
        "published",
        "approved",
        "stock_visibility_state",
        "cash_on_delivery",
        "featured",
        "seller_featured",
        "current_stock",
        "unit",
        "weight",
        "min_qty",
        "low_stock_quantity",
        "discount",
        "discount_type",
        "discount_start_date",
        "discount_end_date",
        "starting_bid",
        "auction_start_date",
        "auction_end_date",
        "tax",
        "tax_type",
        "shipping_type",
        "shipping_cost",
        "is_quantity_multiplied",
        "est_shipping_days",
        "num_of_sale",
        "meta_title",
        "meta_description",
        "meta_img",
        "pdf",
        "slug",
        "earn_point",
        "refundable",
        "rating",
        "barcode",
        "digital",
        "auction_product",
        "file_name",
        "file_path",
        "external_link",
        "external_link_btn",
        "wholesale_product"
    ];

    protected $guarded = ['choice_attributes'];
    protected $with = ['product_translations', 'taxes', 'thumbnail'];

    public function getTranslation($field = '', $lang = false) {
        $lang = $lang == false ? App::getLocale() : $lang;
        $product_translations = $this->product_translations->where('lang', $lang)->first();
        return $product_translations != null ? $product_translations->$field : $this->$field;
    }

    public function product_translations() {
        return $this->hasMany(ProductTranslation::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orderDetails() {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class)->where('status', 1);
    }

    public function wishlists() {
        return $this->hasMany(Wishlist::class);
    }

    public function stocks() {
        return $this->hasMany(ProductStock::class);
    }

    public function taxes() {
        return $this->hasMany(ProductTax::class);
    }

    public function flash_deal_product() {
        return $this->hasOne(FlashDealProduct::class);
    }

    public function bids() {
        return $this->hasMany(AuctionProductBid::class);
    }

    public function thumbnail() {
        return $this->belongsTo(Upload::class, 'thumbnail_img');
    }

    public function scopePhysical($query) {
        return $query->where('digital', 0);
    }

    public function scopeDigital($query) {
        return $query->where('digital', 1);
    }

    public function carts() {
        return $this->hasMany(Cart::class);
    }

    public function scopeIsApprovedPublished($query) {
        return $query->where('approved', '1')->where('published', 1);
    }

    public function productStock() {
        return $this->hasOne(ProductStock::class);
    }

    protected function getFirstDimensionValueFromArray($index) {
        $choice_options = json_decode($this->choice_options);
        $value = $choice_options[0]->values[0];
        $dimensions = explode('x', $value);

        return $dimensions[$index];
    }

    public function length() {
        return $this->getFirstDimensionValueFromArray(0);
    }

    public function width() {
        return $this->getFirstDimensionValueFromArray(1);
    }

    public function height() {
        return $this->getFirstDimensionValueFromArray(2);
    }

    /*protected static function boot(){
        parent::boot();

        static::created(function($product){
            ProductTax::create(['product_id' => $product->id]);
            ProductTax::create(['product_id' => $product->id]);
        });
    }*/
}
