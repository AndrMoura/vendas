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

//Route::get('/admin', 'AdminController@index')->middleware('admin');

Route::group(['middleware' => 'auth'], function () 
{

    Route::get('/profile/{id}', 'UserProfileController@index');
    Route::get('/profile/{id}/edit', 'UserProfileController@edit');
    Route::post('/profile/{id}', 'UserProfileController@postEdit');

});

Route::get('password/reset/{token?}', 'Auth\ResetPasswordController@showResetFrom');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

Route::group(['middleware' => 'admin'], function () 
{
    Route::get('/manage/products', 'AdminController@viewProducts');
    Route::get('/manage/products/edit/{id}', 'AdminController@editProduct');
    Route::get('/manage/products/add', 'AdminController@addProduct');
    Route::post('/manage/products', 'AdminController@addProduct');
    Route::get('/manage/users', 'AdminController@users');
    Route::get('/manage/users/{id}', 'AdminController@manageUser');
});

