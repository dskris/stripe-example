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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Task 1: Make payment
Route::get('/payment', 'PaymentController@index')->name('paymentpage');
Route::post('/charge', 'PaymentController@charge')->name('charge');

//Task 2: Create Monthly Plan
Route::get('monthly-plan', 'PaymentController@monthlyPlan')->name('monthlyPlan');
Route::post('monthly-plan', 'PaymentController@createMonthlyPlan')->name('createMonthlyPlan');

//Task 3: Subscribe For a plan
Route::get('subscribe', 'PaymentController@subscribeForm')->name('subscribeForm');
Route::post('subscribe', 'PaymentController@subscribe')->name('subscribe');