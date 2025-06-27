<?php

use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\BusinessDateNonWorkingController;
use App\Http\Controllers\BusinessWorkingHoursController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProfileController;
use App\Http\Controllers\Seller\ShopController;
use App\Http\Controllers\Seller\OrderController as PdfOrderController;

//Upload
Route::group(['prefix' => 'seller', 'middleware' => ['seller', 'verified', 'phone-verified', 'user', 'prevent-back-history'], 'as' => 'seller.'], function () {
    Route::controller(AizUploadController::class)->group(function () {
        Route::any('/uploads', 'index')->name('uploaded-files.index');
        Route::any('/uploads/create', 'create')->name('uploads.create');
        Route::any('/uploads/file-info', 'file_info')->name('my_uploads.info');
        Route::get('/uploads/destroy/{id}', 'destroy')->name('my_uploads.destroy');
        Route::post('/bulk-uploaded-files-delete', 'bulk_uploaded_files_delete')->name('bulk-uploaded-files-delete');
    });
});
Route::group(['namespace' => 'App\Http\Controllers\Seller', 'prefix' => 'seller', 'middleware' => ['seller', 'verified', 'phone-verified', 'user', 'prevent-back-history', 'auth:sanctum'], 'as' => 'seller.'], function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });
    // Pdf
    Route::post('/orders/generatePDF', [PdfOrderController::class, 'generatePDF'])->name('orders.generatePDF');

    // Product
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index')->name('products');
        Route::get('/product/create', 'create')->name('products.create');
        Route::post('/products/store/', 'store')->name('products.store');
        Route::get('/product/{id}/edit', 'edit')->name('products.edit');
        Route::post('/products/update/{product}', 'update')->name('products.update');
        Route::get('/products/duplicate/{id}', 'duplicate')->name('products.duplicate');
        Route::post('/products/seller/featured', 'updateFeatured')->name('products.featured');
        Route::post('/products/published', 'updatePublished')->name('products.published');
        Route::get('/products/destroy/{id}', 'destroy')->name('products.destroy');
        Route::post('/products/bulk-delete', 'bulk_product_delete')->name('products.bulk-delete');
        Route::post('/products/add-brands', 'add_brands')->name('products.add-brands');
        Route::get('/products/oem/{sku}', 'oem')->name('products.oem');
    });
    // Product Bulk Upload
    Route::controller(ProductBulkUploadController::class)->group(function () {
        Route::get('/product-bulk-upload/index', 'index')->name('product_bulk_upload.index');
        Route::post('/product-bulk-upload/store', 'bulk_upload')->name('bulk_product_upload');
        Route::group(['prefix' => 'bulk-upload/download'], function () {
            Route::get('/category', 'pdf_download_category')->name('pdf.download_category');
            Route::get('/brand', 'pdf_download_brand')->name('pdf.download_brand');
        });
    });
    // Digital Product
    Route::controller(DigitalProductController::class)->group(function () {
        Route::get('/digitalproducts', 'index')->name('digitalproducts');
        Route::get('/digitalproducts/create', 'create')->name('digitalproducts.create');
        Route::post('/digitalproducts/store', 'store')->name('digitalproducts.store');
        Route::get('/digitalproducts/{id}/edit', 'edit')->name('digitalproducts.edit');
        Route::post('/digitalproducts/update/{product}', 'update')->name('digitalproducts.update');
        Route::get('/digitalproducts/destroy/{id}', 'destroy')->name('digitalproducts.destroy');
        Route::get('/digitalproducts/download/{id}', 'download')->name('digitalproducts.download');
    });
    //Coupon
    Route::resource('coupon', CouponController::class);
    Route::controller(CouponController::class)->group(function () {
        Route::post('/coupon/get_form', 'get_coupon_form')->name('coupon.get_coupon_form');
        Route::post('/coupon/get_form_edit', 'get_coupon_form_edit')->name('coupon.get_coupon_form_edit');
        Route::get('/coupon/destroy/{id}', 'destroy')->name('coupon.destroy');
    });
    //Order
    Route::resource('orders', OrderController::class);
    Route::controller(OrderController::class)->group(function () {
        Route::post('/orders/update_delivery_status', 'update_delivery_status')->name('orders.update_delivery_status');
        Route::post('/orders/update_payment_status', 'update_payment_status')->name('orders.update_payment_status');
    });
    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoice/{order_id}', 'invoice_download')->name('invoice.download');
    });
    // Route::get('invoice/{order_id}',[InvoiceController::class, 'invoice_download'])->name('invoice.download');
    //Review
    Route::controller(ReviewController::class)->group(function () {
        Route::get('/reviews', 'index')->name('reviews');
    });
    // Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
    //Shop
    Route::controller(ShopController::class)->group(function () {
        //AQUI esta el index
        Route::get('/shop', 'index')->name('shop.index');
        Route::post('/shop/update', 'update')->name('shop.update');
        Route::get('/shop/apply-for-verification', 'verify_form')->name('shop.verify');
        Route::post('/shop/verification_info_store', 'verify_form_store')->name('shop.verify.store');
    });
    //crear horario laboral de negocio, para agregar la ruta tiene que agregar el prefijo seller
    Route::get('business_working_hours', [BusinessWorkingHoursController::class, 'index'])->name('business_working_hours.index');
    Route::get('business_working_hours/create', [BusinessWorkingHoursController::class, 'create'])->name('business_working_hours.create');
    Route::post('business_working_hours', [BusinessWorkingHoursController::class, 'store'])->name('business_working_hours.store');
    Route::get('business_working_hours/{id}', [BusinessWorkingHoursController::class, 'show'])->name('business_working_hours.show');
    Route::get('business_working_hours/{id}/edit', [BusinessWorkingHoursController::class, 'edit'])->name('business_working_hours.edit');
    Route::put('business_working_hours/{id}', [BusinessWorkingHoursController::class, 'update'])->name('business_working_hours.update');
    Route::delete('business_working_hours/{id}', [BusinessWorkingHoursController::class, 'destroy'])->name('business_working_hours.destroy');
    //crear fechas no laborables de la empresa
    Route::get('business-dates-non-workings', [BusinessDateNonWorkingController::class, 'index'])->name('business_dates_non_workings.index');
    Route::get('business-dates-non-workings/create', [BusinessDateNonWorkingController::class, 'create'])->name('business_dates_non_workings.create');
    Route::post('business-dates-non-workings', [BusinessDateNonWorkingController::class, 'store'])->name('business_dates_non_workings.store');
    Route::get('business-dates-non-workings/{id}', [BusinessDateNonWorkingController::class, 'show'])->name('business_dates_non_workings.show');
    Route::get('business-dates-non-workings/{id}/edit', [BusinessDateNonWorkingController::class, 'edit'])->name('business_dates_non_workings.edit');
    Route::put('business-dates-non-workings/{id}', [BusinessDateNonWorkingController::class, 'update'])->name('business_dates_non_workings.update');
    Route::delete('business-dates-non-workings/{id}', [BusinessDateNonWorkingController::class, 'destroy'])->name('business_dates_non_workings.destroy');
    //Payments
    Route::resource('payments', PaymentController::class);
    // Profile Settings
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::post('/profile/update/{id}', 'update')->name('profile.update');
    });
    // Address
    Route::resource('addresses', AddressController::class);
    Route::controller(AddressController::class)->group(function () {
        Route::post('/get-states', 'getStates')->name('get-state');
        Route::post('/get-cities', 'getCities')->name('get-city');
        Route::post('/address/update/{id}', 'update')->name('addresses.update');
        Route::get('/addresses/destroy/{id}', 'destroy')->name('addresses.destroy');
        Route::get('/addresses/set_default/{id}', 'set_default')->name('addresses.set_default');
    });
    // Money Withdraw Requests
    Route::controller(SellerWithdrawRequestController::class)->group(function () {
        Route::get('/money-withdraw-requests', 'index')->name('money_withdraw_requests.index');
        Route::post('/money-withdraw-request/store', 'store')->name('money_withdraw_request.store');
    });
    // Commission History
    Route::controller(CommissionHistoryController::class)->group(function () {
        Route::get('/commission-history', 'index')->name('commission-history.index');
    });
    //Conversations
    Route::controller(ConversationController::class)->group(function () {
        Route::get('/conversations', 'index')->name('conversations.index');
        Route::get('/conversations/show/{id}', 'show')->name('conversations.show');
        Route::post('conversations/refresh', 'refresh')->name('conversations.refresh');
        Route::post('conversations/message/store', 'message_store')->name('conversations.message_store');
    });
    // product query (comments) show on seller panel
    Route::controller(ProductQueryController::class)->group(function () {
        Route::get('/product-queries', 'index')->name('product_query.index');
        Route::get('/product-queries/{id}', 'show')->name('product_query.show');
        Route::put('/product-queries/{id}', 'reply')->name('product_query.reply');
    });
    // Support Ticket
    Route::controller(SupportTicketController::class)->group(function () {
        Route::get('/support_ticket', 'index')->name('support_ticket.index');
        Route::post('/support_ticket/store', 'store')->name('support_ticket.store');
        Route::get('/support_ticket/show/{id}', 'show')->name('support_ticket.show');
        Route::post('/support_ticket/reply', 'ticket_reply_store')->name('support_ticket.reply_store');
    });
    // Notifications
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/all-notification', 'index')->name('all-notification');
    });
});
