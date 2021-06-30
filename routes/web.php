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

Route::get('/', function () {
    return redirect()->route('meals.index');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::resource('meals', 'MealController');
    Route::resource('orders', 'OrderController');
    Route::get('/advisor', 'MealController@advisor')->name('meals.advisor');
    Route::post('/cart/add', 'CartController@store')->name('cart.add');
    Route::get('/basket', 'CartController@index')->name('cart.index');
    Route::get('/basket/{item}/increase', 'CartController@increase')->name('cart.increase');
    Route::get('/basket/{item}/decrease', 'CartController@decrease')->name('cart.decrease');
    Route::get('/basket/{item}/delete', 'CartController@destroy')->name('cart.destroy');
    Route::get('/orders/{order}/payments', 'PaymentController@checkout')->name('payments.checkout');
    Route::get('/order/place', 'OrderController@store')->name('orders.place');
    Route::get('/orders/{order}/collect', 'OrderController@collect')->name('orders.collect');
    Route::get('/orders/{order}/find', 'OrderController@find')->name('orders.find');
    Route::post('/payments/{order}/make','PaymentController@store')->name('payments.make');
    Route::get('/payments/{payment_pipeline}/processing/{order}', 'PaymentPipelineController@show')->name('payments.processing');
});
