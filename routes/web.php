<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\CustomerProductController;
use App\Http\Controllers\CustomerWorkshopRequestsController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FollowSellerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LoginTokenEmail;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Payment\MercadopagoController;
use App\Http\Controllers\Payment\PaypalController;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\ProductQueryController;
use App\Http\Controllers\ProfileWorkshopController;
use App\Http\Controllers\PurchaseHistoryController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\ReenviarController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceWorkshopProposalMarkNotificationController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\VerificationPhoneController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Workshop\AcceptServiceWorkshopProposalMarkNotificationController;
use App\Http\Controllers\Workshop\DashboardController;
use App\Http\Controllers\Workshop\MarkAsReadAndRedirectController;
use App\Http\Controllers\Workshop\WorkshopAdditionalChargeController;
use App\Http\Controllers\Workshop\WorkshopClientRequestController;
use App\Http\Controllers\Workshop\WorkshopNotificationController;
use App\Http\Controllers\workshopAdditionalMarkNotificationController;
use Illuminate\Support\Facades\Route;
use Mindee\Client;
use Mindee\Product\InternationalId\InternationalIdV2;


Route::get('/fetch-modelss/{make}/{year}', [ArticleController::class, 'fetchModelss']);
Route::get('/fetch-brands-by-keyword', [ArticleController::class, 'fetchBrandsByKeyword'])->name('fetch.brands.by.keyword');
Route::get('test/send-notification', [PushNotificationController::class, 'sendPushNotification']);

Route::get('test/test/mindee', function () {
    $mindeeClient = new Client(config('app.mindee_api_key'));

    $registered_cedule = '';
    $document_cedule = '';

//    $upload = Upload::find($this->cedula_id);
    $file_name = 'uploads/all/CEDULA.jpg'; //$upload->file_name;
    $filePath = public_path($file_name);

    // Load a file from disk
    $inputSource = $mindeeClient->sourceFromPath("$filePath");
    // Parse the file asynchronously
//    $apiResponse = $mindeeClient->enqueueAndParse(InternationalIdV2::class, $inputSource);

//    $document_cedule = (string)$apiResponse->document->inference->prediction->documentNumber->value;

    // Load RNC
    $registered_rnc = '';
    $document_rnc = '';

    $file_name = 'uploads/all/RNC.pdf'; //$upload->file_name;
    $filePath = public_path($file_name);

    //check RNC document
    $inputSource = $mindeeClient->sourceFromPath("$filePath");
    // Parse the file asynchronously
    $apiResponse = $mindeeClient->enqueueAndParse(InternationalIdV2::class, $inputSource);

    // $document_rnc = (string)$apiResponse->document->inference->prediction->documentNumber->value;

    \Illuminate\Support\Facades\Cache::set('api_response', $apiResponse->getRawHttp());

    dd($apiResponse, $document_rnc);

});
Route::post('/loginfront', [AdminController::class, 'loginfront'])->name('loginfront');
Route::get('products/test', 'App\Http\Controllers\Api\V2\ProductController@index');
//Esto es para verificar correo de la cuenta del usuario que recien se registra
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/webhook/pedidosya', [WebhookController::class, 'handleWebhook']);
Route::post('/configure-webhook', [WebhookController::class, 'configureWebhook']);

//Esto es para verificar el rcn o cedula
Route::post('rcn_doc/verify', [RegisterController::class, 'consultarRNCRute'])->name('verification.rcn_doc.verify');
//Esto es para verificar el numero movil
Route::post('phone/verify', [VerificationPhoneController::class, 'store'])->name('verification.store');
//verificar que el correo exite en el formulario de registro comprador negocio y taller
Route::post('email/checkCedula', [EmailController::class, 'checkCedula'])->name('email.checkCedula');
//verificar que el correo exite en el formulario de registro comprador negocio y taller
Route::post('email/checkRnc', [EmailController::class, 'checkRnc'])->name('email.checkRnc');
//verificar que el correo exite en el formulario de registro comprador negocio y taller
Route::post('email/check', [EmailController::class, 'check'])->name('email.check');
//verificar que el numero exite en el formulario de registro comprador negocio y taller
Route::post('phone/check', [PhoneController::class, 'check'])->name('phone.check');
Route::post('tel/check', [PhoneController::class, 'checktel'])->name('tel.check');
//reeenviar el codigo al usuario por si no lo envio antes por correo
Route::post('reenviar-codigo', [ReenviarController::class, 'reenviarCodigo'])->name('reenviarCodigo.codigo');
//reeenviar el codigo al usuario por si no lo envio antes por numero
Route::post('reenviar-codigo-sms', [ReenviarController::class, 'resendVerificationMessage'])->name('reenviarCodigoSMS.sms');





#######################login emai##################
//login desde el correo boton, para iniciarle seccion desde el email
Route::get('/workshop/login/{token}', [LoginTokenEmail::class, 'login'])->name('workshop.login');
Route::get('/customer/login_client/{token}', [LoginTokenEmail::class, 'login_client'])->name('customer.login_client');
Route::get('/workshop/login_taller/{token}', [LoginTokenEmail::class, 'login_taller'])->name('workshop.login_taller');
######################fin login email##############
//AQUI AGREGO LOS DIFERENTES TIPOS DE USUARIOS Y QUE este verificado y autenticado
//ruta para taller panel
Route::group(['middleware' => ['auth', 'user', 'temporary-password-check', 'verified', 'phone-verified']], function () {
    //rutas de solicitud
    //#########################################################
    Route::post('workshop_Client', [WorkshopClientRequestController::class, 'store'])->name('register.workshop_Client.store');
    Route::get('envia', [WorkshopClientRequestController::class, 'envia'])->name('register.workshop_Client.envia');
    //rutas protegidas por el tipo de usuario middleware workshop
    Route::prefix('workshop')->middleware('workshop', 'seller')->group(function () {
        //###################home#####################
        Route::get('dashboard', [DashboardController::class, 'index'])->name('workshop.dashboard');

        #############################profile #########################
        Route::get('profile-workshop', [ProfileWorkshopController::class, 'index'])->name('workshop.profile.index');
        Route::get('profile-workshop/create', [ProfileWorkshopController::class, 'create'])->name('workshop.profile.create');
        Route::post('profile-workshop', [ProfileWorkshopController::class, 'store'])->name('workshop.profile.store');
        Route::get('profile-workshop/{id}', [ProfileWorkshopController::class, 'show'])->name('workshop.profile.show');
        Route::get('profile-workshop/{id}/edit', [ProfileWorkshopController::class, 'edit'])->name('workshop.profile.edit');
        Route::put('profile-workshop/{id}', [ProfileWorkshopController::class, 'update'])->name('workshop.profile.update');
        Route::delete('profile-workshop/{id}', [ProfileWorkshopController::class, 'destroy'])->name('workshop.profile.destroy');
        ############################fin profile##################################
        //###################workshop_additional_charges#####################
        // Route::get('WorkshopAdditional', [WorkshopAdditionalChargeController::class, 'index'])->name('workshop.WorkshopAdditional.index');
        // Route::get('WorkshopAdditional/create', [WorkshopAdditionalChargeController::class, 'create'])->name('workshop.WorkshopAdditional.create');
        Route::post('WorkshopAdditional', [WorkshopAdditionalChargeController::class, 'store'])->name('workshop.WorkshopAdditional.store');
        // Route::get('WorkshopAdditional/{id}', [WorkshopAdditionalChargeController::class, 'show'])->name('workshop.WorkshopAdditional.show');
        // Route::get('WorkshopAdditional/{id}/edit', [WorkshopAdditionalChargeController::class, 'edit'])->name('workshop.WorkshopAdditional.edit');
        // Route::put('WorkshopAdditional/{id}', [WorkshopAdditionalChargeController::class, 'update'])->name('workshop.WorkshopAdditional.update');
        Route::delete('WorkshopAdditional/{id}', [WorkshopAdditionalChargeController::class, 'destroy'])->name('workshop.WorkshopAdditional.destroy');
        #######################fin workshop_additional_charges################
        ####################notifications###########################################
        Route::get('mark_all_notifications', [WorkshopNotificationController::class, 'mark_all_notifications'])->name('workshop.mark_all_notifications');
        Route::get('mark_a_notification/{notification_id}', [WorkshopNotificationController::class, 'mark_a_notification'])->name('workshop.mark_a_notification');
        //marcar notificacion aceptar
        Route::get('mark_a_accept_taller_notification/{notification_id}', [AcceptServiceWorkshopProposalMarkNotificationController::class, 'mark_a_accept_taller_notification'])->name('workshop.mark_a_accept_taller_notification');
        //este es para marcar todas las notificacioones como visto, redirecciona
        Route::get('mark-as-read-and-redirect', [MarkAsReadAndRedirectController::class, 'markAsReadAndRedirect'])->name('mark-as-read-and-redirect');
        #########################fin notifications####################################
    });
});
Route::controller(DemoController::class)->group(function () {
    Route::get('/demo/cron_1', 'cron_1');
    Route::get('/demo/cron_2', 'cron_2');
    Route::get('/convert_assets', 'convert_assets');
    Route::get('/convert_category', 'convert_category');
    Route::get('/convert_tax', 'convertTaxes');
    Route::get('/insert_product_variant_forcefully', 'insert_product_variant_forcefully');
    Route::get('/update_seller_id_in_orders/{id_min}/{id_max}', 'update_seller_id_in_orders');
    Route::get('/migrate_attribute_values', 'migrate_attribute_values');
});
Route::get('/refresh-csrf', function () {
    return csrf_token();
});
// AIZ Uploader
Route::controller(AizUploadController::class)->group(function () {
    Route::post('/aiz-uploader', 'show_uploader');
    Route::post('/aiz-uploader/upload', 'upload');
    Route::get('/aiz-uploader/get-uploaded-files', 'get_uploaded_files');
    Route::post('/aiz-uploader/get_file_by_ids', 'get_preview_files');
    Route::get('/aiz-uploader/download/{id}', 'attachment_download')->name('download_attachment');
});

Route::group(['middleware' => 'prevent-back-history'], function () {
    Auth::routes(['verify' => true]);
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/email-change/callback', 'email_change_callback')->name('email_change.callback');
    Route::post('/password/reset/email/submit', 'reset_password_with_code')->name('password.update');

    //Home Page
    Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('home');
    Route::post('/home/section/featured', 'load_featured_section')->name('home.section.featured');
    Route::post('/home/section/articles', 'load_articles_section')->name('home.section.articles');
    Route::post('/home/section/todays-deal', 'load_todays_deal_section')->name('home.section.todays_deal');
    Route::post('/home/section/best-selling', 'load_best_selling_section')->name('home.section.best_selling');
    Route::post('/home/section/newest-products', 'load_newest_product_section')->name('home.section.newest_products');
    Route::post('/home/section/home-categories', 'load_home_categories_section')->name('home.section.home_categories');
    Route::post('/home/section/best-sellers', 'load_best_sellers_section')->name('home.section.best_sellers');
    //category dropdown menu ajax call
    Route::post('/category/nav-element-list', 'get_category_items')->name('category.elements');
    //Flash Deal Details Page
    Route::get('/flash-deals', 'all_flash_deals')->name('flash-deals');
    Route::get('/flash-deal/{slug}', 'flash_deal_details')->name('flash-deal-details');
    //Todays Deal Details Page
    Route::get('/todays-deal', 'todays_deal')->name('todays-deal');
    Route::get('/product/{slug}', 'product')->name('product');
    Route::post('/product/variant-price', 'variant_price')->name('products.variant_price');
    Route::get('/shop/{slug}', 'shop')->name('shop.visit');
    Route::get('/shop/{slug}/{type}', 'filter_shop')->name('shop.visit.type');
    Route::get('/customer-packages', 'premium_package_index')->name('customer_packages_list_show');
    Route::get('/brands', 'all_brands')->name('brands.all');
    Route::get('/categories', 'all_categories')->name('categories.all');
    Route::get('/sellers', 'all_seller')->name('sellers');
    Route::get('/coupons', 'all_coupons')->name('coupons.all');
    Route::get('/inhouse', 'inhouse_products')->name('inhouse.all');
    // Policies
    Route::get('/seller-policy', 'sellerpolicy')->name('sellerpolicy');
    Route::get('/return-policy', 'returnpolicy')->name('returnpolicy');
    Route::get('/support-policy', 'supportpolicy')->name('supportpolicy');
    Route::get('/terms', 'terms')->name('terms');
    Route::get('/privacy-policy', 'privacypolicy')->name('privacypolicy');
    Route::get('/track-your-order', 'trackOrder')->name('orders.track');

    /**
     * section articles
     */
    Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('load_articles', [ArticleController::class, 'load_articles'])->name('articles.load_articles');
    Route::get('load_articles2', [ArticleController::class, 'load_articles2'])->name('articles.load_articles2');
    Route::get('get_articles', [ArticleController::class, 'getArticles'])->name('articles.get_articles');


    Route::get('select/articles', [ArticleController::class, 'select'])->name('select.index');
    Route::get('get-marca/{brand_id}', [ArticleController::class, 'getMarcaByBrand'])->name('articles.getModelByBrand');
    Route::get('get-model/{productSelectVal}', [ArticleController::class, 'getModelsBy'])->name('products.getModelsBy');
    Route::get('products-by-subcategory/{subcategoryId}', [ArticleController::class, 'getProductsBySubCategory'])->name('products.getProductsBySubCategory');
    Route::get('add_articles_modal', [ArticleController::class, 'addArticlesModal'])->name('add_articles_modal');
    Route::post('article/store', [ArticleController::class, 'store'])->name('articles.store');

    Route::post('delete/articles/{id}', [ArticleController::class, 'delete_article'])->name('delete.article');
    Route::get('/fetch-makes/{year}', [ArticleController::class, 'fetchMakesByCategory']);
// Route::get('/fetch-categories', [ArticleController::class, 'fetchCategories']);
    Route::get('/fetch-categories', [ArticleController::class, 'fetchMainCategories'])->name('fetch.categories');
    Route::get('/fetch-brands', [ArticleController::class, 'fetchBrandsByCategory'])->name('fetch.brands');
    Route::get('/fetch-brands-by-category', [ArticleController::class, 'fetchBrandsByCategory']);
    Route::get('/fetch-models', [ArticleController::class, 'fetchModels'])->name('fetch-models');
});
// Language Switch
Route::post('/language', [LanguageController::class, 'changeLanguage'])->name('language.change');
// Currency Switch
Route::post('/currency', [CurrencyController::class, 'changeCurrency'])->name('currency.change');
Route::get('/sitemap.xml', function () {
    return base_path('sitemap.xml');
});
// Classified Product
Route::controller(CustomerProductController::class)->group(function () {
    Route::get('/customer-products', 'customer_products_listing')->name('customer.products');
    Route::get('/customer-products?category={category_slug}', 'search')->name('customer_products.category');
    Route::get('/customer-products?city={city_id}', 'search')->name('customer_products.city');
    Route::get('/customer-products?q={search}', 'search')->name('customer_products.search');
    Route::get('/customer-product/{slug}', 'customer_product')->name('customer.product');
});
// Search
Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'index')->name('search');
    Route::get('/search_category', 'indexcategory')->name('search_category');
    Route::get('/search?keyword={search}', 'index')->name('suggestion.search');
    Route::post('/ajax-search', 'ajax_search')->name('search.ajax');
    Route::get('/category/{category_slug}', 'listingByCategory')->name('products.category');
    Route::get('/brand/{brand_slug}', 'listingByBrand')->name('products.brand');
});
// Cart
Route::controller(CartController::class)->group(function () {
    Route::get('/carrito', 'index')->name('cart')->middleware('hasItemsInCart');
    Route::post('/carrito/show-cart-modal', 'showCartModal')->name('cart.showCartModal');
    Route::post('/carrito/addtocart', 'addToCart')->name('cart.addToCart');
    Route::post('/carrito/removeFromCart', 'removeFromCart')->name('cart.removeFromCart');
    Route::post('/carrito/updateQuantity', 'updateQuantity')->name('cart.updateQuantity');
});
//Paypal START
Route::controller(PaypalController::class)->group(function () {
    Route::get('/paypal/payment/done', 'getDone')->name('payment.done');
    Route::get('/paypal/payment/cancel', 'getCancel')->name('payment.cancel');
});
//Mercadopago START
Route::controller(MercadopagoController::class)->group(function () {
    Route::any('/mercadopago/payment/done', 'paymentstatus')->name('mercadopago.done');
    Route::any('/mercadopago/payment/cancel', 'callback')->name('mercadopago.cancel');
});
//Mercadopago

//Stipe Start
Route::controller(StripeController::class)->group(function () {
    Route::get('stripe', 'stripe');
    Route::post('/stripe/create-checkout-session', 'create_checkout_session')->name('stripe.get_token');
    Route::any('/stripe/payment/callback', 'callback')->name('stripe.callback');
    Route::get('/stripe/success', 'success')->name('stripe.success');
    Route::get('/stripe/cancel', 'cancel')->name('stripe.cancel');
});
//Stripe END
// Compare
Route::controller(CompareController::class)->group(function () {
    Route::get('/compare', 'index')->name('compare');
    Route::get('/compare/reset', 'reset')->name('compare.reset');
    Route::post('/compare/addToCompare', 'addToCompare')->name('compare.addToCompare');
    Route::get('/compare/details/{id}', 'details')->name('compare.details');
});
// Subscribe
Route::resource('subscribers', SubscriberController::class);
Route::group(['middleware' => ['user', 'temporary-password-check', 'verified', 'phone-verified', 'unbanned']], function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard')->middleware(['prevent-back-history']);
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/new-user-verification', 'new_verify')->name('user.new.verify');
        Route::post('/new-user-email', 'update_email')->name('user.change.email');
        Route::post('/user/update-profile', 'userProfileUpdate')->name('user.profile.update');
    });
    Route::get('/all-notifications', [NotificationController::class, 'index'])->name('all-notifications');
});
Route::group(['middleware' => ['customer', 'temporary-password-check', 'verified', 'phone-verified', 'unbanned', 'auth']], function () {

    // Checkout Routes
    Route::prefix('verificar-pedido')->name('checkout.')->middleware(['hasItemsInCart'])->group(function () {
        Route::controller(CheckoutController::class)->group(function () {
            Route::get('direccion-de-envio', 'getShippingInfo')->name('shipping_info');
            Route::post('metodo-de-pago', 'storeDeliveryInfo')->name('store_delivery_info');
            Route::get('confirmacion-de-orden', 'orderConfirmed')->name('order_confirmed');
            Route::post('realizar-pago', 'checkout')->name('payment');
            Route::post('aplicar-cupon', 'applyCouponCode')->name('apply_coupon_code');
            Route::post('remover-cupon', 'removeCouponCode')->name('remove_coupon_code');
            //Club point
            Route::post('aplicar-lapieza-puntos', 'applyClubPoint')->name('apply_club_point');
            Route::post('remover-lapieza-puntos', 'removeClubPoint')->name('remove_club_point');
        });

        Route::view('medio-de-envio', 'frontend.delivery_info')->name('store_shipping_infostore');
    });

    // Purchase History
    Route::resource('purchase_history', PurchaseHistoryController::class);
    Route::controller(PurchaseHistoryController::class)->group(function () {
        Route::get('/purchase_history/details/{id}', 'purchase_history_details')->name('purchase_history.details');
        Route::get('/purchase_history/destroy/{id}', 'order_cancel')->name('purchase_history.destroy');
        Route::get('digital-purchase-history', 'digital_index')->name('digital_purchase_history.index');
        Route::get('/digital-products/download/{id}', 'download')->name('digital-products.download');
        Route::get('/re-order/{id}', 're_order')->name('re_order');
    });
    ##############//solicitudes##################
    Route::get('Customer_Workshop_Request', [CustomerWorkshopRequestsController::class, 'index'])->name('frontend.user.workshop_request.index');
    // Route::get('Customer_Workshop_Request/create', [CustomerWorkshopRequestsController::class, 'create'])->name('frontend.user.workshop_request.create');
    // Route::post('Customer_Workshop_Request', [CustomerWorkshopRequestsController::class, 'store'])->name('frontend.user.workshop_request.store');
    // Route::get('Customer_Workshop_Request/{id}', [CustomerWorkshopRequestsController::class, 'show'])->name('frontend.user.workshop_request.show');
    // Route::get('Customer_Workshop_Request/{id}/edit', [CustomerWorkshopRequestsController::class, 'edit'])->name('frontend.user.workshop_request.edit');
    Route::put('Customer_Workshop_Request/{id}', [CustomerWorkshopRequestsController::class, 'update'])->name('frontend.user.workshop_request.update');
    // Route::delete('Customer_Workshop_Request/{id}', [CustomerWorkshopRequestsController::class, 'destroy'])->name('frontend.user.workshop_request.destroy');
    Route::post('Customer_Workshop_Request/packages/purchase', [CustomerWorkshopRequestsController::class, 'purchase_package'])->name('frontend.user.workshop_request.purchase_package');
    // Falta hacer test a esto
    Route::get('customer/sslcommerz/success', [CustomerWorkshopRequestsController::class, 'success'])->name('frontend.user.workshop_request.success');
    Route::get('customer/sslcommerz/fail', [CustomerWorkshopRequestsController::class, 'fail'])->name('frontend.user.workshop_request.fail');
    Route::get('customer/sslcommerz/cancel', [CustomerWorkshopRequestsController::class, 'cancel'])->name('frontend.user.workshop_request.cancel');
    #############################################
    // Wishlist
    Route::resource('wishlists', WishlistController::class);
    Route::post('/wishlists/remove', [WishlistController::class, 'remove'])->name('wishlists.remove');
    // Follow
    Route::controller(FollowSellerController::class)->group(function () {
        Route::get('/followed-seller', 'index')->name('followed_seller');
        Route::get('/followed-seller/store', 'store')->name('followed_seller.store');
        Route::get('/followed-seller/remove', 'remove')->name('followed_seller.remove');
    });
    // Wallet
    Route::controller(WalletController::class)->middleware(['auth:sanctum', 'wallet'])->group(function () {
        Route::get('/wallet', 'index')->name('wallet.index');
        Route::post('/recharge', 'recharge')->name('wallet.recharge');
    });
    // Support Ticket
    Route::resource('support_ticket', SupportTicketController::class);
    Route::post('support_ticket/reply', [SupportTicketController::class, 'seller_store'])->name('support_ticket.seller_store');
    // Customer Package
    Route::post('/customer-packages/purchase', [CustomerPackageController::class, 'purchase_package'])->name('customer_packages.purchase');
    // Customer Product
    Route::resource('customer_products', CustomerProductController::class);
    Route::controller(CustomerProductController::class)->group(function () {
        Route::get('/customer_products/{id}/edit', 'edit')->name('customer_products.edit');
        Route::post('/customer_products/published', 'updatePublished')->name('customer_products.published');
        Route::post('/customer_products/status', 'updateStatus')->name('customer_products.update.status');
        Route::get('/customer_products/destroy/{id}', 'destroy')->name('customer_products.destroy');
    });
    // Product Review
    Route::post('/product-review-modal', [ReviewController::class, 'product_review_modal'])->name('product_review_modal');
    #################### Notifications ###########################################
    Route::get('mark_all_customer_notifications', [ServiceWorkshopProposalMarkNotificationController::class, 'mark_all_customer_notifications'])->name('customer.mark_all_customer_notifications');
    Route::get('mark_a_customer_notification/{notification_id}', [ServiceWorkshopProposalMarkNotificationController::class, 'mark_a_customer_notification'])->name('customer.mark_a_customer_notification');
    Route::get('mark_a_additional_notification/{notification_id}', [workshopAdditionalMarkNotificationController::class, 'mark_a_additional_notification'])->name('customer.mark_a_additional_notification');
    #################### Notifications ###########################################
});
Route::get('translation-check/{check}', [LanguageController::class, 'get_translation']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('invoice/{order_id}', [InvoiceController::class, 'invoice_download'])->name('invoice.download');
    // Reviews
    Route::resource('/reviews', ReviewController::class);
    // Product Conversation
    Route::resource('conversations', ConversationController::class);
    Route::controller(ConversationController::class)->group(function () {
        Route::get('/conversations/destroy/{id}', 'destroy')->name('conversations.destroy');
        Route::post('conversations/refresh', 'refresh')->name('conversations.refresh');
    });
    // Product Query
    Route::resource('product-queries', ProductQueryController::class);
    Route::resource('messages', MessageController::class);
    //Address crud
    Route::resource('addresses', AddressController::class);
    Route::controller(AddressController::class)->group(function () {
        Route::post('/get-states', 'getStates')->name('get-state');
        Route::post('/get-cities', 'getCities')->name('get-city');
        // Route::post('/addresses/update/{id}', 'update')->name('addresses.update');
        Route::put('/addresses/update/{id}', 'update')->name('addresses.update');
        Route::get('/addresses/destroy/{id}', 'destroy')->name('addresses.destroy');
        Route::get('/addresses/set-default/{id}', 'set_default')->name('addresses.set_default');
    });
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'as' => 'admin.'], function () {
    Route::resource('shipping-companies', 'App\Http\Controllers\Backend\ShippingManagementController');
});
Route::get('/checkout-payment-detail', [StripeController::class, 'checkout_payment_detail']);

//Blog Section
Route::controller(BlogController::class)->group(function () {
    Route::get('/blog', 'all_blog')->name('blog');
    Route::get('/blog/{slug}', 'blog_details')->name('blog.details');
});
Route::controller(PageController::class)->group(function () {
    //mobile app balnk page for webview
    Route::get('/mobile-page/{slug}', 'mobile_custom_page')->name('mobile.custom-pages');
    //Custom page
    Route::get('/{slug}', 'show_custom_page')->name('custom-pages.show_custom_page');
});
// Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'as' => 'admin.'], function () {
    // Route::post('shipping-companies/send-request', [App\Http\Controllers\Backend\ShippingManagementController::class, 'sendShippingRequest'])
    //     ->name('shipping-companies.send-request');
// });
Route::post('shipping-companies/send-request', [App\Http\Controllers\Backend\ShippingManagementController::class, 'sendShippingRequest'])
        ->name('shipping-companies.send-request');