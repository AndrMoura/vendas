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

//Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/admin', 'AdminController@index')->middleware('admin');

Route::get('/home', 'HomePageController@index');
Route::get('/home/search', 'HomePageController@search');

//CART------------------------------------------------------------------------------------------------------------------\
Route::post('/cart', 'ProductController@saveCart');
Route::post('/updateCart','ProductController@updateCart');
Route::post('/home', 'ProductController@saveCartHomePage');
Route::post('/cart/delete', 'ProductController@deleteCart');

Route::get('/cart', 'ProductController@showCart');
//------------------------------------------------------------------------------------------------------------------------

Route::get('/product/{id}', 'ProductController@index')->name('produtos');

Route::group(['middleware' => 'auth'], function () 
{

    Route::get('/profile/{id}', 'UserProfileController@index');
    Route::post('/profile/{id}', 'UserProfileController@postEdit');

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


Route::post('/create-payment', 'PaymentController@createPayment');
Route::post('execute-payment','PaymentController@executePayment');
