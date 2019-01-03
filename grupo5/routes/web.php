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

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();
Route::get('/', 'HomePageController@index');
Route::get('/home/search', 'HomePageController@search');

//CART------------------------------------------------------------------------------------------------------------------\
Route::get('/cart', 'ProductController@showCart')->name('cart');
Route::post('/cart', 'ProductController@saveCart');
Route::post('/updateCart','ProductController@updateCart');
Route::post('/home', 'ProductController@saveCartHomePage');
Route::post('/cart/delete', 'ProductController@deleteCart');
//------------------------------------------------------------------------------------------------------------------------

Route::get('/product/{id}', 'ProductController@index')->name('produtos');

Route::group(['middleware' => 'user'], function ()
{
    Route::get('/cart', 'ProductController@showCart')->name('cart');
    Route::post('/cart', 'ProductController@saveCart');
    Route::post('/updateCart','ProductController@updateCart');
    Route::post('/home', 'ProductController@saveCartHomePage');
    Route::post('/cart/delete', 'ProductController@deleteCart');
    Route::get('/profile/{id}', 'UserProfileController@index');
    Route::post('/profile/{id}', 'UserProfileController@postEdit');
    Route::get('/profile/{id}/paymentDetails','PaymentController@getSalesId');
    Route::post('/create-payment', 'PaymentController@createPayment');
    Route::post('execute-payment','PaymentController@executePayment');
    Route::get('/pdf', 'PDFController@getPDF');
});

Route::get('password/reset/{token?}', 'Auth\ResetPasswordController@showResetFrom');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');


Route::group(['middleware' => 'admin'], function () 
{
    Route::get('/manage/products', 'AdminController@viewProducts');
    Route::post('/manage/products/edit', 'AdminController@editProduct');
    Route::post('/manage/products/delete', 'AdminController@deleteProduct');
    Route::post('/manage/products', 'AdminController@addProduct');
    Route::get('/manage/users', 'AdminController@users');
    Route::get('/manage/users/{id}', 'AdminController@manageUser');
});

