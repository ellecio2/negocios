<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\Delivery\V1\PedidosYaController;
use App\Http\Controllers\Api\ShippingCompanyApiSimulatorController;
use App\Http\Controllers\Mensajeria\WorkShopController;
use App\Http\Controllers\Workshop\WorkshopClientRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V2\BlogController;

use App\Http\Controllers\Api\V2\CouponController;
use App\Http\Controllers\Api\V2\ImageController;
use App\Http\Controllers\Api\V2\ShippingCompanyController;
use App\Http\Controllers\Backend\ShippingManagementController;
use App\Http\Controllers\Api\V2\Seller\ShopController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V2\BusinessHoursController;
use App\Http\Controllers\Api\V2\CompareController;
use App\Http\Controllers\Api\V2\OrdersController;


// Rutas para gestionar el proceso de envío con PedidosYa y transportadoras
Route::post('shipping/pickup-and-ship', [ShippingManagementController::class, 'managePedidosYaPickupAndShipping']);
Route::get('/shipping/track/{id}', [ShippingManagementController::class, 'trackShippingProcess'])->name('shipping.track');

// Ruta para confirmar detalles de envío de PedidosYa (si no existe ya)
Route::post('/shipping/pedidosya/confirm', [PedidosYaController::class, 'confirmShipping']);

// Ruta para consultar estado de un envío en PedidosYa
Route::get('/shipping/pedidosya/status/{trackingNumber}', [PedidosYaController::class, 'getShippingStatus']);


Route::post('shipping-quote-by-address', [ShippingManagementController::class, 'calculateShippingByAddress']);
Route::post('shipping-companies/send-request', [ShippingManagementController::class, 'sendShippingRequest'])
    ->name('shipping-companies.send-request');
// Route::post('shipping-api-test', [ShippingManagementController::class, 'sendShippingRequest']);
// En routes/api.php (para el simulador)
Route::prefix('simulator')->group(function () {
    Route::post('/shipping-request', [ShippingCompanyApiSimulatorController::class, 'receiveShippingRequest']);
    Route::get('/shipping-status/{tracking_number}', [ShippingCompanyApiSimulatorController::class, 'getShippingStatus']);
});
// En routes/api.php
Route::options('/{any}', function () {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN');
})->where('any', '.*');
Route::get('test-tb', function () {
    return response()->json(['message' => 'Ruta de prueba funcionando correctamente']);
});
Route::group(['prefix' => 'v2/auth', 'middleware' => ['app_language']], function () {
    Route::post('login', 'App\Http\Controllers\Api\V2\AuthController@login');
    Route::post('signup', 'App\Http\Controllers\Api\V2\AuthController@signup');
    Route::post('social-login', 'App\Http\Controllers\Api\V2\AuthController@socialLogin');
    Route::post('password/forget_request', 'App\Http\Controllers\Api\V2\PasswordResetController@forgetRequest');
    Route::post('password/confirm_reset', 'App\Http\Controllers\Api\V2\PasswordResetController@confirmReset');
    Route::post('password/resend_code', 'App\Http\Controllers\Api\V2\PasswordResetController@resendCode');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('logout', 'App\Http\Controllers\Api\V2\AuthController@logout');
        Route::get('account-deletion', 'App\Http\Controllers\Api\V2\AuthController@account_deletion');
        Route::get('user', 'App\Http\Controllers\Api\V2\AuthController@user');
        Route::get('resend_code', 'App\Http\Controllers\Api\V2\AuthController@resendCode');
        Route::post('confirm_code', 'App\Http\Controllers\Api\V2\AuthController@confirmCode');
        Route::post('save-new-register-phone', 'App\Http\Controllers\Api\V2\AuthController@resavePhone');
        Route::post('save-new-register-email', 'App\Http\Controllers\Api\V2\AuthController@resaveEmail');
    });

    Route::post('info', 'App\Http\Controllers\Api\V2\AuthController@getUserInfoByAccessToken');


});

Route::controller(CompareController::class)->group(function () {
    Route::get('/compare', 'index');
    Route::get('/compare/reset', 'reset');
    Route::post('/compare/addToCompare', 'addToCompare'); // Esta es la que debería usar
    Route::get('/compare/list', 'getCompareList');
    Route::post('/compare/remove', 'removeFromCompare');
});

Route::group(['prefix' => 'v2', 'middleware' => ['app_language']], function () {

    Route::get('coupons', [CouponController::class, 'index']);

    Route::get('coupon-shows', [CouponShowController::class, 'index']);
    Route::get('coupon-shows/{couponShow}', [CouponShowController::class, 'show']);
    // auction products routes
    Route::get('auction/products', [AuctionProductController::class, 'index']);
    Route::get('auction/bided-products', [AuctionProductController::class, 'bided_products_list'])->middleware('auth:sanctum');
    Route::get('auction/purchase-history', [AuctionProductController::class, 'user_purchase_history'])->middleware('auth:sanctum');
    Route::get('auction/products/{id}', [AuctionProductController::class, 'details_auction_product']);
    Route::post('auction/place-bid', [AuctionProductBidController::class, 'store'])->middleware('auth:sanctum');
    Route::get('pos/user-cart-data', 'App\Http\Controllers\Seller\PosController@test');

    Route::prefix('delivery-boy')->group(function () {
        Route::get('dashboard-summary/{id}', 'App\Http\Controllers\Api\V2\DeliveryBoyController@dashboard_summary')->middleware('auth:sanctum');
        Route::get('deliveries/completed/{id}', 'App\Http\Controllers\Api\V2\DeliveryBoyController@completed_delivery')->middleware('auth:sanctum');
        Route::get('deliveries/cancelled/{id}', 'App\Http\Controllers\Api\V2\DeliveryBoyController@cancelled_delivery')->middleware('auth:sanctum');
        Route::get('deliveries/on_the_way/{id}', 'App\Http\Controllers\Api\V2\DeliveryBoyController@on_the_way_delivery')->middleware('auth:sanctum');
        Route::get('deliveries/picked_up/{id}', 'App\Http\Controllers\Api\V2\DeliveryBoyController@picked_up_delivery')->middleware('auth:sanctum');
        Route::get('deliveries/assigned/{id}', 'App\Http\Controllers\Api\V2\DeliveryBoyController@assigned_delivery')->middleware('auth:sanctum');
        Route::get('collection-summary/{id}', 'App\Http\Controllers\Api\V2\DeliveryBoyController@collection_summary')->middleware('auth:sanctum');
        Route::get('earning-summary/{id}', 'App\Http\Controllers\Api\V2\DeliveryBoyController@earning_summary')->middleware('auth:sanctum');
        Route::get('collection/{id}', 'App\Http\Controllers\Api\V2\DeliveryBoyController@collection')->middleware('auth:sanctum');
        Route::get('earning/{id}', 'App\Http\Controllers\Api\V2\DeliveryBoyController@earning')->middleware('auth:sanctum');
        Route::get('cancel-request/{id}', 'App\Http\Controllers\Api\V2\DeliveryBoyController@cancel_request')->middleware('auth:sanctum');
        Route::post('change-delivery-status', 'App\Http\Controllers\Api\V2\DeliveryBoyController@change_delivery_status')->middleware('auth:sanctum');
        //Delivery Boy Order
        Route::get('purchase-history-details/{id}', 'App\Http\Controllers\Api\V2\DeliveryBoyController@details')->middleware('auth:sanctum');
        Route::get('purchase-history-items/{id}', 'App\Http\Controllers\Api\V2\DeliveryBoyController@items')->middleware('auth:sanctum');
    });

    Route::get('/blogs/active', 'App\Http\Controllers\Api\V2\BlogController@getActiveBlogs');
    Route::get('/blogs/category/{categoryId}', 'App\Http\Controllers\Api\V2\BlogController@getByCategory');
    Route::get('/blogs/{id}', 'App\Http\Controllers\Api\V2\BlogController@show');
    Route::get('/blogs', 'App\Http\Controllers\Api\V2\BlogController@index');

    Route::apiResource('images', ImageController::class)->only('index', 'store');

    Route::get('images', 'App\Http\Controllers\Api\V2\ImageController@index');
    Route::get('images/{id}', 'App\Http\Controllers\Api\V2\ImageController@show');

    Route::post('/shipping-companies', [ShippingCompanyController::class, 'store'])->name('shipping-companies.store');
    Route::get('/shipping-companies/create', [ShippingCompanyController::class, 'create'])->name('shipping-companies.create');
    Route::get('/shipping-companies/edit', [ShippingCompanyController::class, 'edit'])->name('shipping-companies.edit');


    Route::group(['middleware' => ['app_user_unbanned']], function () {
        // customer downloadable product list
        Route::get('/digital/purchased-list', 'App\Http\Controllers\Api\V2\PurchaseHistoryController@digital_purchased_list')->middleware('auth:sanctum');
        Route::get('/purchased-products/download/{id}', 'App\Http\Controllers\Api\V2\DigitalProductController@download')->middleware('auth:sanctum');

        Route::get('wallet/history', 'App\Http\Controllers\Api\V2\WalletController@walletRechargeHistory')->middleware(['auth:sanctum', 'wallet']);
        Route::get('chat/conversations', 'App\Http\Controllers\Api\V2\ChatController@conversations')->middleware('auth:sanctum');
        Route::get('chat/messages/{id}', 'App\Http\Controllers\Api\V2\ChatController@messages')->middleware('auth:sanctum');
        Route::post('chat/insert-message', 'App\Http\Controllers\Api\V2\ChatController@insert_message')->middleware('auth:sanctum');
        Route::get('chat/get-new-messages/{conversation_id}/{last_message_id}', 'App\Http\Controllers\Api\V2\ChatController@get_new_messages')->middleware('auth:sanctum');
        Route::post('chat/create-conversation', 'App\Http\Controllers\Api\V2\ChatController@create_conversation')->middleware('auth:sanctum');
        Route::get('purchase-history', 'App\Http\Controllers\Api\V2\PurchaseHistoryController@index')->middleware('auth:sanctum');
        Route::get('purchase-history-details/{id}', 'App\Http\Controllers\Api\V2\PurchaseHistoryController@details')->middleware('auth:sanctum');
        Route::get('purchase-history-items/{id}', 'App\Http\Controllers\Api\V2\PurchaseHistoryController@items')->middleware('auth:sanctum');
        Route::get('re-order/{id}', 'App\Http\Controllers\Api\V2\PurchaseHistoryController@re_order')->middleware('auth:sanctum');

        Route::prefix('classified')->group(function () {
            Route::get('/own-products', 'App\Http\Controllers\Api\V2\CustomerProductController@ownProducts')->middleware('auth:sanctum');
            Route::delete('/delete/{id}', 'App\Http\Controllers\Api\V2\CustomerProductController@delete')->middleware('auth:sanctum');
            Route::post('/change-status/{id}', 'App\Http\Controllers\Api\V2\CustomerProductController@changeStatus')->middleware('auth:sanctum');
        });

        Route::get('customer/info', 'App\Http\Controllers\Api\V2\CustomerController@show')->middleware('auth:sanctum');

        Route::get('cart-summary', 'App\Http\Controllers\Api\V2\CartController@summary')->middleware('auth:sanctum');
        Route::get('cart-count', 'App\Http\Controllers\Api\V2\CartController@count')->middleware('auth:sanctum');
        Route::post('carts/process', 'App\Http\Controllers\Api\V2\CartController@process')->middleware('auth:sanctum');
        Route::post('carts/add', 'App\Http\Controllers\Api\V2\CartController@add')->middleware('auth:sanctum');
        Route::post('carts/change-quantity', 'App\Http\Controllers\Api\V2\CartController@changeQuantity')->middleware('auth:sanctum');
        Route::apiResource('carts', 'App\Http\Controllers\Api\V2\CartController')->only(['destroy'])->middleware('auth:sanctum');
        Route::put('carts/set-address/{id}', 'App\Http\Controllers\Api\V2\CartController@edit')->middleware('auth:sanctum');
        Route::post('carts', 'App\Http\Controllers\Api\V2\CartController@getList')->middleware('auth:sanctum');
        Route::get('delivery-info', 'App\Http\Controllers\Api\V2\ShippingController@getDeliveryInfo')->middleware('auth:sanctum');
        Route::put('carts/{cart_id}/set-delivery-option', [CartController::class, 'setDeliveryOption'])->middleware('auth:sanctum');

        Route::post('coupon-apply', 'App\Http\Controllers\Api\V2\CheckoutController@apply_coupon_code')->middleware('auth:sanctum');
        Route::post('coupon-remove', 'App\Http\Controllers\Api\V2\CheckoutController@remove_coupon_code')->middleware('auth:sanctum');

        Route::post('update-address-in-cart', 'App\Http\Controllers\Api\V2\AddressController@updateAddressInCart')->middleware('auth:sanctum');

        Route::post('update-shipping-type-in-cart', 'App\Http\Controllers\Api\V2\AddressController@updateShippingTypeInCart')->middleware('auth:sanctum');
        Route::get('get-home-delivery-address', 'App\Http\Controllers\Api\V2\AddressController@getShippingInCart')->middleware('auth:sanctum');
        Route::post('shipping_cost', 'App\Http\Controllers\Api\V2\ShippingController@shipping_cost')->middleware('auth:sanctum');
        Route::post('carriers', 'App\Http\Controllers\Api\V2\CarrierController@index')->middleware('auth:sanctum');

        //Follow
        Route::controller(FollowSellerController::class)->group(function () {
            Route::get('/followed-seller', 'index')->middleware('auth:sanctum');
            Route::get('/followed-seller/store/{id}', [FollowSellerController::class, 'store'])->middleware('auth:sanctum');
            Route::get('/followed-seller/remove/{shopId}', [FollowSellerController::class, 'remove'])->middleware('auth:sanctum');
            Route::get('/followed-seller/check/{shopId}', [FollowSellerController::class, 'checkFollow'])->middleware('auth:sanctum');
        });

        Route::post('reviews/submit', 'App\Http\Controllers\Api\V2\ReviewController@submit')->name('api.reviews.submit')->middleware('auth:sanctum');
        Route::get('shop/user/{id}', 'App\Http\Controllers\Api\V2\ShopController@shopOfUser')->middleware('auth:sanctum');
        Route::get('wishlists-check-product', 'App\Http\Controllers\Api\V2\WishlistController@isProductInWishlist')->middleware('auth:sanctum');
        Route::get('wishlists-add-product', 'App\Http\Controllers\Api\V2\WishlistController@add')->middleware('auth:sanctum');
        Route::get('wishlists-remove-product', 'App\Http\Controllers\Api\V2\WishlistController@remove')->middleware('auth:sanctum');
        Route::get('wishlists', 'App\Http\Controllers\Api\V2\WishlistController@index')->middleware('auth:sanctum');
        Route::apiResource('wishlists', 'App\Http\Controllers\Api\V2\WishlistController')->except(['index', 'update', 'show']);

        Route::get('user/shipping/address', 'App\Http\Controllers\Api\V2\AddressController@addresses')->middleware('auth:sanctum');
        Route::post('user/shipping/create', 'App\Http\Controllers\Api\V2\AddressController@createShippingAddress')->middleware('auth:sanctum');
        Route::post('user/shipping/update', 'App\Http\Controllers\Api\V2\AddressController@updateShippingAddress')->middleware('auth:sanctum');
        Route::post('user/shipping/update-location', 'App\Http\Controllers\Api\V2\AddressController@updateShippingAddressLocation')->middleware('auth:sanctum');
        Route::post('user/shipping/make_default', 'App\Http\Controllers\Api\V2\AddressController@makeShippingAddressDefault')->middleware('auth:sanctum');
        Route::get('user/shipping/delete/{address_id}', 'App\Http\Controllers\Api\V2\AddressController@deleteShippingAddress')->middleware('auth:sanctum');

        Route::get('clubpoint/get-list', 'App\Http\Controllers\Api\V2\ClubpointController@get_list')->middleware('auth:sanctum');
        Route::post('clubpoint/convert-into-wallet', 'App\Http\Controllers\Api\V2\ClubpointController@convert_into_wallet')->middleware(['auth:sanctum', 'wallet']);

        Route::get('refund-request/get-list', 'App\Http\Controllers\Api\V2\RefundRequestController@get_list')->middleware('auth:sanctum');
        Route::post('refund-request/send', 'App\Http\Controllers\Api\V2\RefundRequestController@send')->middleware('auth:sanctum');

        Route::get('bkash/begin', 'App\Http\Controllers\Api\V2\BkashController@begin')->middleware('auth:sanctum');
        Route::get('nagad/begin', 'App\Http\Controllers\Api\V2\NagadController@begin')->middleware('auth:sanctum');


        Route::post('order/store', 'App\Http\Controllers\Api\V2\OrderController@store')->middleware(['auth:sanctum', 'hasItemsInCart']);
        Route::get('order/cancel/{id}', 'App\Http\Controllers\Api\V2\OrderController@order_cancel')->middleware('auth:sanctum');
        Route::get('order/{combined_order_id}/check-workshop-availability', 'App\Http\Controllers\Api\V2\OrderController@checkWorkshopAvailability')->middleware('auth:sanctum');
        Route::get('orders/{order_id}/check-workshop-availability', 'App\Http\Controllers\Api\V2\OrderController@checkWorkshopAvailabilityPerOrder')->middleware('auth:sanctum');
        Route::get('workshops/workshop-request-status', 'App\Http\Controllers\Api\V2\OrderController@workshopRequestStatus')->middleware('auth:sanctum');
        Route::post('workshops/request-service', [WorkshopClientRequestController::class, 'store'])->middleware('auth:sanctum');
        Route::delete('workshops/cancel-request-service', [WorkshopClientRequestController::class, 'cancelRequestService'])->middleware('auth:sanctum');

        Route::get('profile/counters', 'App\Http\Controllers\Api\V2\ProfileController@counters')->middleware('auth:sanctum');

        Route::post('profile/update', 'App\Http\Controllers\Api\V2\ProfileController@update')->middleware('auth:sanctum');

        Route::post('profile/update-device-token', 'App\Http\Controllers\Api\V2\ProfileController@update_device_token')->middleware('auth:sanctum');
        Route::post('profile/update-image', 'App\Http\Controllers\Api\V2\ProfileController@updateImage')->middleware('auth:sanctum');
        Route::post('profile/image-upload', 'App\Http\Controllers\Api\V2\ProfileController@imageUpload')->middleware('auth:sanctum');
        Route::post('profile/check-phone-and-email', 'App\Http\Controllers\Api\V2\ProfileController@checkIfPhoneAndEmailAvailable')->middleware('auth:sanctum');

        Route::post('file/image-upload', 'App\Http\Controllers\Api\V2\FileController@imageUpload')->middleware('auth:sanctum');
        Route::get('file-all', 'App\Http\Controllers\Api\V2\FileController@index')->middleware('auth:sanctum');
        Route::post('file/upload', 'App\Http\Controllers\Api\V2\AizUploadController@upload')->middleware('auth:sanctum');

        Route::get('wallet/balance', 'App\Http\Controllers\Api\V2\WalletController@balance')->middleware(['auth:sanctum', 'wallet']);
        Route::post('wallet/offline-recharge', 'App\Http\Controllers\Api\V2\WalletController@offline_recharge')->middleware(['auth:sanctum', 'wallet']);

        Route::controller(CustomerPackageController::class)->group(function () {
            Route::post('offline/packages-payment', 'purchase_package_offline')->middleware('auth:sanctum');
            Route::post('free/packages-payment', 'purchase_package_free')->middleware('auth:sanctum');
        });
    });

    //end user bann
    Route::controller(OnlinePaymentController::class)->group(function () {
        Route::get('online-pay/init', 'init')->middleware('auth:sanctum');
        Route::get('online-pay/success', 'paymentSuccess');
        Route::get('online-pay/done', 'paymentDone');
        Route::get('online-pay/failed', 'paymentFailed');
    });

    Route::get('get-search-suggestions', 'App\Http\Controllers\Api\V2\SearchSuggestionController@getList');
    Route::get('languages', 'App\Http\Controllers\Api\V2\LanguageController@getList');

    Route::get('classified/all', 'App\Http\Controllers\Api\V2\CustomerProductController@all');
    Route::get('classified/related-products/{id}', 'App\Http\Controllers\Api\V2\CustomerProductController@relatedProducts');
    Route::get('classified/product-details/{id}', 'App\Http\Controllers\Api\V2\CustomerProductController@productDetails');

    Route::get('seller/top', 'App\Http\Controllers\Api\V2\SellerController@topSellers');

    Route::apiResource('banners', 'App\Http\Controllers\Api\V2\BannerController')->only('index');

    Route::get('brands/top', 'App\Http\Controllers\Api\V2\BrandController@top');
    Route::apiResource('brands', 'App\Http\Controllers\Api\V2\BrandController')->only('index');

    Route::apiResource('business-settings', 'App\Http\Controllers\Api\V2\BusinessSettingController')->only('index');

    Route::get('categories/featured', 'App\Http\Controllers\Api\V2\CategoryController@featured');
    Route::get('categories/home', 'App\Http\Controllers\Api\V2\CategoryController@home');
    Route::get('categories/top', 'App\Http\Controllers\Api\V2\CategoryController@top');
    Route::apiResource('categories', 'App\Http\Controllers\Api\V2\CategoryController')->only('index');
    Route::get('sub-categories/{id}', 'App\Http\Controllers\Api\V2\SubCategoryController@index')->name('subCategories.index');

    Route::apiResource('colors', 'App\Http\Controllers\Api\V2\ColorController')->only('index');

    Route::apiResource('currencies', 'App\Http\Controllers\Api\V2\CurrencyController')->only('index');

    Route::apiResource('customers', 'App\Http\Controllers\Api\V2\CustomerController')->only('show')->middleware('auth:sanctum');

    Route::apiResource('general-settings', 'App\Http\Controllers\Api\V2\GeneralSettingController')->only('index');

    Route::apiResource('home-categories', 'App\Http\Controllers\Api\V2\HomeCategoryController')->only('index');

    Route::get('customer/{id}', 'App\Http\Controllers\Api\V2\CustomerController@get')->middleware('auth:sanctum');
    Route::get('filter/categories', 'App\Http\Controllers\Api\V2\FilterController@categories');
    Route::get('filter/brands', 'App\Http\Controllers\Api\V2\FilterController@brands');

    // Route::get('products/admin', 'App\Http\Controllers\Api\V2\ProductController@admin');
    Route::get('products/seller/{id}', 'App\Http\Controllers\Api\V2\ProductController@seller');
    Route::get('products/state/{id}', 'App\Http\Controllers\Api\V2\ProductController@getProductsByStateId');
     Route::get('products/country/{id}', 'App\Http\Controllers\Api\V2\ProductController@getProductsByCountryId');
     Route::get('products/city/{id}', 'App\Http\Controllers\Api\V2\ProductController@getProductsByCitieId');
    Route::get('products/category/{id}', 'App\Http\Controllers\Api\V2\ProductController@category')->name('api.products.category');
    Route::get('products/sub-category/{id}', 'App\Http\Controllers\Api\V2\ProductController@subCategory')->name('products.subCategory');
    Route::get('products/sub-sub-category/{id}', 'App\Http\Controllers\Api\V2\ProductController@subSubCategory')->name('products.subSubCategory');
    Route::get('products/brand/{id}', 'App\Http\Controllers\Api\V2\ProductController@brand')->name('api.products.brand');
    Route::get('products/todays-deal', 'App\Http\Controllers\Api\V2\ProductController@todaysDeal');
    Route::get('products/featured', 'App\Http\Controllers\Api\V2\ProductController@featured');
    Route::get('products/best-seller', 'App\Http\Controllers\Api\V2\ProductController@bestSeller');
    Route::get('products/top-from-seller/{id}', 'App\Http\Controllers\Api\V2\ProductController@topFromSeller');
    Route::get('products/related/{id}', 'App\Http\Controllers\Api\V2\ProductController@related')->name('products.related');
    Route::get('products/slug/{id}', 'App\Http\Controllers\Api\V2\ProductController@bySlug')->name('products.by_slug');
    Route::get('products/featured-from-seller/{id}', 'App\Http\Controllers\Api\V2\ProductController@newFromSeller')->name('products.featuredromSeller');
    Route::get('products/search', 'App\Http\Controllers\Api\V2\ProductController@search');
    Route::post('products/variant/price', 'App\Http\Controllers\Api\V2\ProductController@getPrice');
    // Route::get('products/home', 'App\Http\Controllers\Api\V2\ProductController@home');
    Route::get('products/digital', 'App\Http\Controllers\Api\V2\ProductController@digital')->name('products.digital');
    Route::apiResource('products', 'App\Http\Controllers\Api\V2\ProductController')->except(['store', 'update', 'destroy']);

    //Use this route outside of auth because initialy we created outside of auth we do not need auth initialy
    //We can't change it now because we didn't send token in header from mobile app.
    //We need the upload update Flutter app then we will write it in auth middleware.
    Route::controller(CustomerPackageController::class)->group(function () {
        Route::get("customer-packages", "customer_packages_list");
    });

    // En routes/api.php
    Route::get('product/{slug}', 'App\Http\Controllers\HomeController@productApi');

    Route::get('reviews/product/{id}', 'App\Http\Controllers\Api\V2\ReviewController@index')->name('api.reviews.index');

    Route::get('shops/details/{id}', 'App\Http\Controllers\Api\V2\ShopController@info')->name('shops.info');
    Route::get('shops/products/all/{id}', 'App\Http\Controllers\Api\V2\ShopController@allProducts')->name('shops.allProducts');
    Route::get('shops/products/top/{id}', 'App\Http\Controllers\Api\V2\ShopController@topSellingProducts')->name('shops.topSellingProducts');
    Route::get('shops/products/featured/{id}', 'App\Http\Controllers\Api\V2\ShopController@featuredProducts')->name('shops.featuredProducts');
    Route::get('shops/products/new/{id}', 'App\Http\Controllers\Api\V2\ShopController@newProducts')->name('shops.newProducts');
    Route::get('shops/brands/{id}', 'App\Http\Controllers\Api\V2\ShopController@brands')->name('shops.brands');
    Route::apiResource('shops', 'App\Http\Controllers\Api\V2\ShopController')->only('index');

    Route::get('sliders', 'App\Http\Controllers\Api\V2\SliderController@sliders');
    Route::get('banners-one', 'App\Http\Controllers\Api\V2\SliderController@bannerOne');
    Route::get('banners-two', 'App\Http\Controllers\Api\V2\SliderController@bannerTwo');
    Route::get('banners-three', 'App\Http\Controllers\Api\V2\SliderController@bannerThree');
    Route::get('banners-four', 'App\Http\Controllers\Api\V2\SliderController@bannerFour');
    Route::get('banners-five', 'App\Http\Controllers\Api\V2\SliderController@bannerFive');

    Route::get('policies/seller', 'App\Http\Controllers\Api\V2\PolicyController@sellerPolicy')->name('policies.seller');
    Route::get('policies/support', 'App\Http\Controllers\Api\V2\PolicyController@supportPolicy')->name('policies.support');
    Route::get('policies/return', 'App\Http\Controllers\Api\V2\PolicyController@returnPolicy')->name('policies.return');

    Route::post('get-user-by-access_token', 'App\Http\Controllers\Api\V2\UserController@getUserInfoByAccessToken');

    Route::get('cities', 'App\Http\Controllers\Api\V2\AddressController@getCities');
    Route::get('states', 'App\Http\Controllers\Api\V2\AddressController@getStates');
    Route::get('countries', 'App\Http\Controllers\Api\V2\AddressController@getCountries');

    Route::get('cities-by-state/{state_id}', 'App\Http\Controllers\Api\V2\AddressController@getCitiesByState');
    Route::get('states-by-country/{country_id}', 'App\Http\Controllers\Api\V2\AddressController@getStatesByCountry');

    Route::any('stripe', 'App\Http\Controllers\Api\V2\StripeController@stripe');
    Route::any('stripe/create-checkout-session', 'App\Http\Controllers\Api\V2\StripeController@create_checkout_session')->name('api.stripe.get_token');
    Route::any('stripe/payment/callback', 'App\Http\Controllers\Api\V2\StripeController@callback')->name('api.stripe.callback');
    Route::get('stripe/success', 'App\Http\Controllers\Api\V2\StripeController@payment_success');
    Route::any('stripe/cancel', 'App\Http\Controllers\Api\V2\StripeController@cancel')->name('api.stripe.cancel');

    Route::any('paypal/payment/url', 'App\Http\Controllers\Api\V2\PaypalController@getUrl')->name('api.paypal.url');
    Route::any('paypal/payment/done', 'App\Http\Controllers\Api\V2\PaypalController@getDone')->name('api.paypal.done');
    Route::any('paypal/payment/cancel', 'App\Http\Controllers\Api\V2\PaypalController@getCancel')->name('api.paypal.cancel');

    Route::any('khalti/payment/pay', 'App\Http\Controllers\Api\V2\KhaltiController@pay')->name('api.khalti.url');
    Route::any('khalti/payment/success', 'App\Http\Controllers\Api\V2\KhaltiController@paymentDone')->name('api.khalti.success');
    Route::any('khalti/payment/cancel', 'App\Http\Controllers\Api\V2\KhaltiController@getCancel')->name('api.khalti.cancel');

    Route::any('razorpay/pay-with-razorpay', 'App\Http\Controllers\Api\V2\RazorpayController@payWithRazorpay')->name('api.razorpay.pay_with_razorpay');
    Route::any('razorpay/payment', 'App\Http\Controllers\Api\V2\RazorpayController@payment')->name('api.razorpay.payment');
    Route::post('razorpay/success', 'App\Http\Controllers\Api\V2\RazorpayController@payment_success')->name('api.razorpay.success');

    Route::any('paystack/init', 'App\Http\Controllers\Api\V2\PaystackController@init')->name('api.paystack.init');
    Route::post('paystack/success', 'App\Http\Controllers\Api\V2\PaystackController@payment_success')->name('api.paystack.success');

    Route::any('iyzico/init', 'App\Http\Controllers\Api\V2\IyzicoController@init')->name('api.iyzico.init');
    Route::any('iyzico/callback', 'App\Http\Controllers\Api\V2\IyzicoController@callback')->name('api.iyzico.callback');
    Route::post('iyzico/success', 'App\Http\Controllers\Api\V2\IyzicoController@payment_success')->name('api.iyzico.success');

    Route::get('bkash/api/webpage/{token}/{amount}', 'App\Http\Controllers\Api\V2\BkashController@webpage')->name('api.bkash.webpage');
    Route::any('bkash/api/checkout/{token}/{amount}', 'App\Http\Controllers\Api\V2\BkashController@checkout')->name('api.bkash.checkout');
    Route::any('bkash/api/callback', 'App\Http\Controllers\Api\V2\BkashController@callback')->name('api.bkash.callback');

    Route::any('bkash/api/execute/{token}', 'App\Http\Controllers\Api\V2\BkashController@execute')->name('api.bkash.execute');
    Route::any('bkash/api/fail', 'App\Http\Controllers\Api\V2\BkashController@fail')->name('api.bkash.fail');
    Route::post('bkash/api/success', 'App\Http\Controllers\Api\V2\BkashController@payment_success')->name('api.bkash.success');
    Route::post('bkash/api/process', 'App\Http\Controllers\Api\V2\BkashController@process')->name('api.bkash.process');

    Route::any('nagad/verify/{payment_type}', 'App\Http\Controllers\Api\V2\NagadController@verify')->name('app.nagad.callback_url');
    Route::post('nagad/process', 'App\Http\Controllers\Api\V2\NagadController@process');

    Route::get('sslcommerz/begin', 'App\Http\Controllers\Api\V2\SslCommerzController@begin');
    Route::any('sslcommerz/success', 'App\Http\Controllers\Api\V2\SslCommerzController@payment_success');
    Route::any('sslcommerz/fail', 'App\Http\Controllers\Api\V2\SslCommerzController@payment_fail');
    Route::any('sslcommerz/cancel', 'App\Http\Controllers\Api\V2\SslCommerzController@payment_cancel');

    //AZUL END POINTS
    Route::get('azul/begin', 'App\Http\Controllers\Api\V2\PagoAzulController@begin');
    Route::any('azul/success', 'App\Http\Controllers\Api\V2\PagoAzulController@payment_success');
    Route::any('azul/fail', 'App\Http\Controllers\Api\V2\PagoAzulController@payment_fail');
    Route::any('azul/cancel', 'App\Http\Controllers\Api\V2\PagoAzulController@payment_cancel');

    Route::any('flutterwave/payment/url', 'App\Http\Controllers\Api\V2\FlutterwaveController@getUrl')->name('api.flutterwave.url');
    Route::any('flutterwave/payment/callback', 'App\Http\Controllers\Api\V2\FlutterwaveController@callback')->name('api.flutterwave.callback');

    Route::any('paytm/payment/pay', 'App\Http\Controllers\Api\V2\PaytmController@pay')->name('api.paytm.pay');
    Route::any('paytm/payment/callback', 'App\Http\Controllers\Api\V2\PaytmController@callback')->name('api.paytm.callback');

    Route::controller(InstamojoController::class)->group(function () {
        Route::get('instamojo/pay', 'pay')->middleware('auth:sanctum');
        Route::any('instamojo/success', 'success');
        Route::get('instamojo/failed', 'paymentFailed');
    });

    Route::post('offline/payment/submit', 'App\Http\Controllers\Api\V2\OfflinePaymentController@submit')->name('api.offline.payment.submit');

    Route::get('flash-deals', 'App\Http\Controllers\Api\V2\FlashDealController@index');
    Route::get('flash-deal-products/{id}', 'App\Http\Controllers\Api\V2\FlashDealController@products');
    //Addon list
    Route::get('addon-list', 'App\Http\Controllers\Api\V2\ConfigController@addon_list');
    //Activated social login list
    Route::get('activated-social-login', 'App\Http\Controllers\Api\V2\ConfigController@activated_social_login');
    //Business Sttings list
    Route::post('business-settings', 'App\Http\Controllers\Api\V2\ConfigController@business_settings');
    //Pickup Point list
    Route::get('pickup-list', 'App\Http\Controllers\Api\V2\ShippingController@pickup_list');

    Route::get('google-recaptcha', function () {
        return view("frontend.google_recaptcha.app_recaptcha");
    });


  Route::prefix('orders')->middleware('auth:sanctum')->group(function () {
      Route::get('', [OrdersController::class, 'index']);
      Route::get('combined/{id}', [OrdersController::class, 'show']);
      Route::get('code/{code}', [OrdersController::class, 'showByCode']);
       Route::get('tracking_code/{code}', [OrdersController::class, 'showByTrackingCode']);
  });



    Route::prefix('workshop')->middleware(['workshop', 'auth:sanctum', 'verified'])->group(function () {
        Route::get('categories', '\App\Http\Controllers\Api\V2\Workshops\CategoryController@index');
    });

    Route::get('banks', [BankController::class, 'index']);
    Route::get('itbis', function () {});

    Route::get('business-hours', [BusinessHoursController::class, 'getBusinessHours']);

   Route::get('products/{id}/frequently-bought-together', 'App\Http\Controllers\Api\V2\ProductController@frequentlyBoughtTogether');


});

Route::fallback(function () {
    return response()->json([
        'data' => [],
        'success' => false,
        'status' => 404,
        'message' => 'Invalid Route'
    ]);
});