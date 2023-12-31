<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\BusinessSettingsController;

Route::group(['namespace' => 'Seller', 'prefix' => 'seller', 'as' => 'seller.'], function () {




     Route::get('add-popular-cat', 'popularController@create')->name('create.popular-category');
     Route::post('store-popular-cat', 'popularController@store')->name('store-popular-category');
     Route::get('delete/popular/category/{id}', 'popularController@delete')->name('delete-popular-category');
     
     Route::post('analytics-update', 'BusinessSettingsController@seller_analytics_update')->name('analytics-update');

    /*authentication*/
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('/code/captcha/{tmp}', 'LoginController@captcha')->name('default-captcha');
        Route::get('login', 'LoginController@login')->name('login');
        Route::post('login', 'LoginController@submit');
        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('forgot-password', 'ForgotPasswordController@forgot_password')->name('forgot-password');
        Route::post('forgot-password', 'ForgotPasswordController@reset_password_request');
        Route::get('otp-verification', 'ForgotPasswordController@otp_verification')->name('otp-verification');
        Route::post('otp-verification', 'ForgotPasswordController@otp_verification_submit');
        Route::get('reset-password', 'ForgotPasswordController@reset_password_index')->name('reset-password');
        Route::post('reset-password', 'ForgotPasswordController@reset_password_submit');
    });

    /*authenticated*/
    Route::group(['middleware' => ['seller']], function () {
        //dashboard routes

        Route::get('/get-order-data', 'SystemController@order_data')->name('get-order-data');

        Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
            Route::get('dashboard', 'DashboardController@dashboard');
            Route::get('/', 'DashboardController@dashboard')->name('index');
            Route::post('order-stats', 'DashboardController@order_stats')->name('order-stats');
            Route::post('business-overview', 'DashboardController@business_overview')->name('business-overview');
            Route::get('earning-statistics', 'DashboardController@get_earning_statitics')->name('earning-statistics');
        });

        Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
            Route::post('image-upload', 'ProductController@imageUpload')->name('image-upload');
            Route::get('remove-image', 'ProductController@remove_image')->name('remove-image');
            Route::get('add-new', 'ProductController@add_new')->name('add-new');
            Route::post('add-new', 'ProductController@store');
            Route::post('store/image', 'ProductController@store_image')->name('store_image');
            Route::post('store/thumb/image', 'ProductController@store_thumb_image')->name('store_thumb_image');
            Route::post('/seller/product/upload-variation-image', 'ProductController@uploadVariationImage')->name('upload_variation_image');
            Route::post('status-update', 'ProductController@status_update')->name('status-update');
            Route::get('list', 'ProductController@list')->name('list');
            Route::get('stock-limit-list/{type}', 'ProductController@stock_limit_list')->name('stock-limit-list');
            Route::get('get-variations', 'ProductController@get_variations')->name('get-variations');
            Route::post('update-quantity', 'ProductController@update_quantity')->name('update-quantity');
            Route::get('edit/{id}', 'ProductController@edit')->name('edit');
            Route::get('recomended_active/{id}', 'ProductController@rec_active')->name('recActive');
            Route::get('recomended_inactive/{id}', 'ProductController@rec_inactive')->name('recInActive');
            Route::post('update/{id}', 'ProductController@update')->name('update');
            Route::post('sku-combination', 'ProductController@sku_combination')->name('sku-combination');
            Route::get('get-categories', 'ProductController@get_categories')->name('get-categories');
            Route::get('get-categories-click', 'ProductController@get_categories_click')->name('get-categories-click');
            Route::get('get-sub-categories-click', 'ProductController@get_sub_categories_click')->name('get-sub-categories-click');

            Route::get('get-sub-categories-click', 'ProductController@get_sub_categories_click')->name('get-sub-categories-click');
            Route::get('get-sub-sub-categories-click', 'ProductController@get_sub_sub_categories_click')->name('get-sub-sub-categories-click');


            Route::get('barcode', 'ProductController@get_categories')->name('get-categories');
            Route::get('barcode/{id}', 'ProductController@barcode')->name('barcode');

            Route::delete('delete/{id}', 'ProductController@delete')->name('delete');

            Route::get('view/{id}', 'ProductController@view')->name('view');
            Route::get('bulk-import', 'ProductController@bulk_import_index')->name('bulk-import');
            Route::post('bulk-import', 'ProductController@bulk_import_data');
            Route::get('bulk-export', 'ProductController@bulk_export_data')->name('bulk-export');

            // Ck Editor Upload
            Route::post('/file/upload', 'ProductController@fileUpload')->name('upload');

            //category by product title
            Route::post('/category-wise-product', 'ProductController@categoryByProduct')->name('category_by_product');
        });

        Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
            Route::get('all-product', 'ReportController@all_product')->name('all-product');
            Route::get('stock-product-report', 'ReportController@stock_product_report')->name('stock-product-report');
            Route::get('order-report', 'ReportController@order_report')->name('order-report');
            Route::any('set-date', 'ReportController@set_date')->name('set-date');
        });

        Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
            Route::get('add-new', 'CouponController@add_new')->name('add-new')->middleware('actch');
            Route::post('store-coupon', 'CouponController@store')->name('store-coupon');
            Route::get('update/{id}', 'CouponController@edit')->name('update')->middleware('actch');
            Route::post('update/{id}', 'CouponController@update');
            Route::get('quick-view-details', 'CouponController@quick_view_details')->name('quick-view-details');
            Route::get('status/{id}/{status}', 'CouponController@status_update')->name('status');
            Route::delete('delete/{id}', 'CouponController@delete')->name('delete');

        });

        Route::group(['prefix' => 'deal', 'as' => 'deal.'],function () {
            Route::get('flash', 'DealController@flash_index')->name('flash');
            Route::post('flash', 'DealController@flash_submit');

            // feature deal
            Route::get('feature', 'DealController@feature_index')->name('feature');

            Route::get('day', 'DealController@shipping_index')->name('day');
            Route::post('day', 'DealController@flash_submit');
            Route::post('day-status-update', 'DealController@day_status_update')->name('day-status-update');

            Route::get('day-update/{id}', 'DealController@day_edit')->name('day-update');
            Route::post('day-update/{id}', 'DealController@day_update');
            Route::post('day-delete', 'DealController@day_delete')->name('day-delete');

            Route::get('update/{id}', 'DealController@edit')->name('update');
            Route::get('edit/{id}', 'DealController@feature_edit')->name('edit');

            Route::get('delete/{id}', 'DealController@delete')->name('delete');

            Route::post('update/{id}', 'DealController@update')->name('update');
            Route::post('status-update', 'DealController@status_update')->name('status-update');
            Route::post('feature-status', 'DealController@feature_status')->name('feature-status');
            Route::get('discount-update', 'DealController@discount_update')->name('discount-update');
            Route::post('featured-update', 'DealController@featured_update')->name('featured-update');
            Route::get('add-product/{deal_id}', 'DealController@add_product')->name('add-product');
            Route::get('free-shipping-seller-product/{deal_id}', 'DealController@fshipping_product')->name('fship-product');
            Route::post('add-product/{deal_id}', 'DealController@add_product_submit');
            Route::post('delete-product', 'DealController@delete_product')->name('delete-product');
            Route::post('delete-fshipping-product', 'DealController@delete_fshipping_product')->name('delete-fshipping-product');
        });

        Route::group(['prefix' => 'transaction', 'as' => 'transaction.'], function () {
            Route::get('order-list', 'TransactionController@order_list')->name('order-list');
            Route::get('transaction-export', 'TransactionController@export')->name('transaction-export');
            Route::get('expense-list', 'TransactionController@expense_list')->name('expense-list');
        });

        //refund request
        Route::group(['prefix' => 'refund', 'as' => 'refund.'], function () {
            Route::get('list/{status}', 'RefundController@list')->name('list');
            Route::get('details/{id}', 'RefundController@details')->name('details');
            Route::get('inhouse-order-filter', 'RefundController@inhouse_order_filter')->name('inhouse-order-filter');
            Route::post('refund-status-update', 'RefundController@refund_status_update')->name('refund-status-update');

        });
        Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
            Route::get('list/{status}', 'OrderController@list')->name('list');
            Route::get('details/{id}', 'OrderController@details')->name('details');
            Route::get('generate-invoice/{id}', 'OrderController@generate_invoice')->name('generate-invoice');
            Route::post('status', 'OrderController@status')->name('status');
            Route::post('amount-date-update', 'OrderController@amount_date_update')->name('amount-date-update');
            Route::post('productStatus', 'OrderController@productStatus')->name('productStatus');
            Route::post('payment-status', 'OrderController@payment_status')->name('payment-status');
            Route::post('digital-file-upload-after-sell', 'OrderController@digital_file_upload_after_sell')->name('digital-file-upload-after-sell');

            Route::post('update-deliver-info','OrderController@update_deliver_info')->name('update-deliver-info');
            Route::get('add-delivery-man/{order_id}/{d_man_id}', 'OrderController@add_delivery_man')->name('add-delivery-man');
            Route::post('export-order-data/{status}', 'OrderController@bulk_export_data')->name('order-bulk-export');
            Route::post('export-packing-data/{status}', 'OrderController@export_packing_data')->name('export-packing-data');
            Route::get('barcode/{id}', 'OrderController@barcode')->name('barcode');
            
            // Define the route for generating invoices for multiple orders
			
        Route::post('generate-multiple-invoices', 'OrderController@generateAllInvoices')->name('generate-multiple-invoices');
        
        Route::post('seller/orders/download-selected-packing-pdf', 'OrderController@downloadSelectedPackingPDF')
            ->name('download-selected-packing-pdf');
            
        });
        //pos management
        Route::group(['prefix' => 'pos', 'as' => 'pos.'], function () {
            Route::get('/', 'POSController@index')->name('index');
            Route::get('quick-view', 'POSController@quick_view')->name('quick-view');
            Route::post('variant_price', 'POSController@variant_price')->name('variant_price');
            Route::post('add-to-cart', 'POSController@addToCart')->name('add-to-cart');
            Route::post('remove-from-cart', 'POSController@removeFromCart')->name('remove-from-cart');
            Route::post('cart-items', 'POSController@cart_items')->name('cart_items');
            Route::post('update-quantity', 'POSController@updateQuantity')->name('updateQuantity');
            Route::post('empty-cart', 'POSController@emptyCart')->name('emptyCart');
            Route::post('tax', 'POSController@update_tax')->name('tax');
            Route::post('discount', 'POSController@update_discount')->name('discount');
            Route::get('customers', 'POSController@get_customers')->name('customers');
            Route::post('order', 'POSController@place_order')->name('order');
            Route::get('orders', 'POSController@order_list')->name('orders');
            Route::get('order-details/{id}', 'POSController@order_details')->name('order-details');
            Route::post('digital-file-upload-after-sell', 'POSController@digital_file_upload_after_sell')->name('digital-file-upload-after-sell');
            Route::get('invoice/{id}', 'POSController@generate_invoice');
            Route::any('store-keys', 'POSController@store_keys')->name('store-keys');
            Route::get('search-products','POSController@search_product')->name('search-products');
            Route::get('order-bulk-export','POSController@bulk_export_data')->name('order-bulk-export');


            Route::post('coupon-discount', 'POSController@coupon_discount')->name('coupon-discount');
            Route::get('change-cart','POSController@change_cart')->name('change-cart');
            Route::get('new-cart-id','POSController@new_cart_id')->name('new-cart-id');
            Route::post('remove-discount','POSController@remove_discount')->name('remove-discount');
            Route::get('clear-cart-ids','POSController@clear_cart_ids')->name('clear-cart-ids');
            Route::get('get-cart-ids','POSController@get_cart_ids')->name('get-cart-ids');

            Route::post('customer-store', 'POSController@customer_store')->name('customer-store');
        });
        //Product Reviews

        Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
            Route::get('list', 'ReviewsController@list')->name('list');
            Route::post('review-reply', 'ReviewsController@storeReviewReply')->name('reply');
            Route::get('export', 'ReviewsController@export')->name('export')->middleware('actch');
            Route::get('status/{id}/{status}', 'ReviewsController@status')->name('status');

        });

        // Messaging
        Route::group(['prefix' => 'messages', 'as' => 'messages.'], function () {
            Route::get('/chat/{type}', 'ChattingController@chat')->name('chat');
            Route::get('/ajax-message-by-user', 'ChattingController@ajax_message_by_user')->name('ajax-message-by-user');
            Route::post('/ajax-seller-message-store', 'ChattingController@ajax_seller_message_store')->name('ajax-seller-message-store');
        });

        Route::get('/contact-us', 'ChattingController@viewContact')->name('contact');

        // profile

        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::get('view', 'ProfileController@view')->name('view');
            Route::get('update/{id}', 'ProfileController@edit')->name('update');
            Route::post('update/{id}', 'ProfileController@update');
            Route::post('settings-password', 'ProfileController@settings_password_update')->name('settings-password');

            Route::get('bank-edit/{id}', 'ProfileController@bank_edit')->name('bankInfo');
            Route::post('bank-update/{id}', 'ProfileController@bank_update')->name('bank_update');

        });
        Route::group(['prefix' => 'shop', 'as' => 'shop.'], function () {
            Route::get('view', 'ShopController@view')->name('view');
            Route::get('edit/{id}', 'ShopController@edit')->name('edit');
            Route::post('update/{id}', 'ShopController@update')->name('update');
        });

        Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.'], function () {
            Route::post('request', 'WithdrawController@w_request')->name('request');
            Route::post('add-request-claim', 'WithdrawController@add_claim')->name('request.claim');
            Route::delete('close/{id}', 'WithdrawController@close_request')->name('close');
        });



        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {

            Route::group(['prefix' => 'shipping-method', 'as' => 'shipping-method.'], function () {
                Route::get('add', 'ShippingMethodController@index')->name('add');
                Route::post('add', 'ShippingMethodController@store');
                Route::get('edit/{id}', 'ShippingMethodController@edit')->name('edit');
                Route::put('update/{id}', 'ShippingMethodController@update')->name('update');
                Route::post('delete', 'ShippingMethodController@delete')->name('delete');
                Route::post('status-update', 'ShippingMethodController@status_update')->name('status-update');
            });

            Route::group(['prefix' => 'shipping-type', 'as' => 'shipping-type.'], function () {
                Route::post('store', 'ShippingTypeController@store')->name('store');
            });
            Route::group(['prefix' => 'category-shipping-cost', 'as' => 'category-shipping-cost.'], function () {
                Route::post('store', 'CategoryShippingCostController@store')->name('store');
            });

            Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.'], function () {
                Route::get('list', 'WithdrawController@list')->name('list');
                Route::get('cancel/{id}', 'WithdrawController@close_request')->name('cancel');
                Route::post('status-filter', 'WithdrawController@status_filter')->name('status-filter');
            });

        });

        Route::group(['prefix' => 'delivery-man', 'as' => 'delivery-man.'], function () {
            Route::get('add', 'DeliveryManController@index')->name('add');
            Route::post('store', 'DeliveryManController@store')->name('store');
            Route::get('list', 'DeliveryManController@list')->name('list');
            Route::get('preview/{id}', 'DeliveryManController@preview')->name('preview');
            Route::get('edit/{id}', 'DeliveryManController@edit')->name('edit');
            Route::post('update/{id}', 'DeliveryManController@update')->name('update');
            Route::delete('delete/{id}', 'DeliveryManController@delete')->name('delete');
            Route::post('search', 'DeliveryManController@search')->name('search');
            Route::post('status-update', 'DeliveryManController@status')->name('status-update');
            Route::get('earning-statement/{id}', 'DeliveryManController@earning_statement')->name('earning-statement');
            Route::get('collect-cash/{id}', 'DeliveryManCashCollectController@collect_cash')->name('collect-cash');
            Route::post('cash-receive/{id}', 'DeliveryManCashCollectController@cash_receive')->name('cash-receive');
            Route::get('withdraw-list', 'DeliverymanWithdrawController@withdraw')->name('withdraw-list');
            Route::get('withdraw-list-export', 'DeliverymanWithdrawController@export')->name('withdraw-list-export');
            Route::post('status-filter', 'DeliverymanWithdrawController@status_filter')->name('status-filter');
            Route::get('withdraw-view/{withdraw_id}', 'DeliverymanWithdrawController@withdraw_view')->name('withdraw-view');
            Route::post('withdraw-status/{id}', 'DeliverymanWithdrawController@withdrawStatus')->name('withdraw_status');

            Route::get('order-history-log/{id}', 'DeliveryManController@order_history_log')->name('order-history-log');
            Route::get('order-wise-earning/{id}', 'DeliveryManController@order_wise_earning')->name('order-wise-earning');
            Route::get('ajax-order-status-history/{order}', 'DeliveryManController@ajax_order_status_history')->name('ajax-order-status-history');

            Route::group(['prefix' => 'emergency-contact', 'as' => 'emergency-contact.'], function (){
                Route::get('/', 'EmergencyContactController@emergency_contact')->name('index');
                Route::post('add', 'EmergencyContactController@add')->name('add');
                Route::post('ajax-status-change', 'EmergencyContactController@ajax_status_change')->name('ajax-status-change');
                Route::delete('destroy', 'EmergencyContactController@destroy')->name('destroy');
            });

            Route::get('rating/{id}', 'DeliveryManController@rating')->name('rating');
        });
    });

});
