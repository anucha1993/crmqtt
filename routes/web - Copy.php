<?php

use Illuminate\Support\Facades\Route;

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
//route artsist
Route::get('clear/cache',function(){
    \Artisan::call('cache:clear');
});
Route::get('clear/config',function(){
    \Artisan::call('config:clear');
});
Route::get('migrate',function(){
    \Artisan::call('migrate');
});
Route::get('storagelink',function(){
    \Artisan::call('storage:link');
});
//route artsist end


Route::get('/old', function () {
    return view('layouts.default');
});

Route::get('/createadmin', 'AuthController@create');
Route::get('/login', 'AuthController@login');
Route::get('/forgotpassword', 'AuthController@forgotpassword');
Route::post('/updatepassword', 'AuthController@updatepassword')->name('auth.updaepass');
Route::post('/singin', 'AuthController@postSignin')->name('users.singin');


Route::group(['middleware' => ['authweb']], function () {

    Route::get('/logout', 'AuthController@logout');
    Route::get('/changpass', function () {return view('users.changpass');});

    Route::get('/', function () {
        return view('dashboard');
    });

    Route::group(['prefix' => 'master-users'], function () {
        Route::get('/','UsersController@index');
    });

    Route::group(['prefix' => 'quotation'], function () {
        Route::get('/','QuotationController@index');
        Route::get('/add','QuotationController@add');
        Route::get('/view/{id}','QuotationController@view');
        Route::post('save', 'QuotationController@save')->name('quotation.save');
        Route::post('update', 'QuotationController@update')->name('quotation.update');
        Route::post('updatestatus', 'QuotationController@updatestatus')->name('quotation.updatestatus');
    });

    Route::group(['prefix' => 'orders'], function () {

        Route::get('/','OrdersController@index');
		Route::post('barcode_ajax','OrdersController@barcode_ajax')->name('orders.barcode_ajax');
		Route::post('barcode_sub_ajax','OrdersController@barcode_sub_ajax')->name('orders.barcode_sub_ajax');
        Route::get('/add/{id}','OrdersController@add');
        Route::post('save','OrdersController@save')->name('orders.save');
        Route::get('/view/{id}','OrdersController@view');
        Route::post('update','OrdersController@update')->name('orders.update');
        Route::post('updatequotation','OrdersController@updatequotation')->name('orders.updatequotation');
        Route::get('/payment/{id}','OrdersController@payment');

        Route::get('list', 'OrdersController@list');


        Route::group(['prefix' => 'delivery'], function () {
            Route::get('/list/{id}','DeliveryController@list');
            Route::get('/add/{id}','DeliveryController@add');
            Route::post('save','DeliveryController@save')->name('delivery.save');
            Route::get('view/{id}','DeliveryController@view');
            Route::get('view/{id}/{id2}','DeliveryController@view');
            Route::post('update','DeliveryController@update')->name('delivery.update');
        });
    });



    Route::group(['prefix' => 'customer'], function () {
        Route::get('/','CustomerController@index');
		Route::get('barcode','CustomerController@barcode');
        Route::get('add','CustomerController@add');
        Route::post('save','CustomerController@save')->name('customer.save');
        Route::get('edit/{id}','CustomerController@edit');
        Route::post('update','CustomerController@update')->name('customer.update');
        Route::get('pocketmoney/{id}','CustomerController@pocketmoney');
        Route::post('pocketmoneysave','CustomerController@pocketmoneysave')->name('customer.pocketmoneysave');
    });

    Route::group(['prefix' => 'report'], function () {
        Route::get('/orders','ReportsController@orders');
        Route::get('/orderslist','ReportsController@orderslist');
        Route::post('/exportorder','ReportsController@exportorder');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/','UsersController@index');
        Route::get('add','UsersController@add');
        Route::post('save','UsersController@save')->name('users.save');
        Route::get('edit/{id}','UsersController@edit');
        Route::post('update','UsersController@update')->name('users.update');
        Route::post('changpass','UsersController@changpass')->name('users.changpass');
    });

    Route::group(['prefix' => 'noti'], function () {
        Route::post('updatenoti','NotificationsController@updatenoti')->name('noti.updatenoti');
    });



});
