<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/checkmail', 'AuthController@checkmail')->name('auth.checkmail');

Route::group(['prefix' => 'quotation'], function () {
    Route::get('list', 'QuotationController@list');

});
Route::group(['prefix' => 'orders'], function () {
     Route::get('list', 'OrdersController@list');
	Route::get('/update_payment_method_type_code', 'OrdersController@update_payment_method_type_code')->name('orders.update_payment_method_type_code');

});
Route::group(['prefix' => 'payment_history'], function () {
	Route::get('/add_payment_history_record', 'PaymentHistoryController@add_payment_history_record')->name('payment_history.add_payment_history_record');

});
Route::group(['prefix' => 'payment_method_pocket_money'], function () {
	Route::get('/add_payment_method_pocket_money', 'PaymentMethodPocketMoneyController@add_payment_method_pocket_money')->name('payment_method_pocket_money.add_payment_method_pocket_money');
	Route::get('/update_pocket_money_amount', 'PaymentMethodPocketMoneyController@update_pocket_money_amount')->name('payment_method_pocket_money.update_pocket_money_amount');
});
Route::group(['prefix' => 'customer'], function () {
    Route::get('list', 'CustomerController@list');
    Route::post('/searchcustomer', 'CustomerController@searchcustomer')->name('customer.searchcustomer');
    Route::post('/datacustomer', 'CustomerController@datacustomer')->name('customer.datacustomer');
    Route::post('/amphoe', 'CustomerController@getamphoe')->name('customer.amphoe');
    Route::post('/district', 'CustomerController@getdistrict')->name('customer.district');
    Route::post('/cehckstore', 'CustomerController@cehckstore')->name('customer.cehckstore');
    Route::post('/cehcktextvat', 'CustomerController@cehckTextVat')->name('customer.cehcktextvat');
    Route::post('/cehckcustomermail', 'CustomerController@cehckCustomerMail')->name('customer.cehckcustomermail');
    Route::get('pocketmoney/list','CustomerController@pocketmoneylist');
});
Route::group(['prefix' => 'reports'], function () {
    Route::get('list', 'ReportsController@list');
});

Route::group(['prefix' => 'users'], function () {
    Route::get('list', 'UsersController@list');
    Route::post('cehckemail', 'UsersController@cehckemail')->name('users.cehckemail');
    Route::post('cehckname', 'UsersController@cehckname')->name('users.cehckname');
});
Route::group(['prefix' => 'product'], function () {
    Route::post('/producttype', 'ProductController@prodecttype')->name('product.producttype');
    Route::post('/productsize', 'ProductController@productsize')->name('product.productsize');
    Route::post('/productcountunit', 'ProductController@productcountunit')->name('product.productcountunit');
});
Route::group(['prefix' => 'products'], function () {
    Route::post('/list', 'ProductController@list')->name('products.list');
    
});
