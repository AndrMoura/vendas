<?php

use Illuminate\Http\Request;

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




/*Route::middleware('guest')->get('/user', function (Request $request) {
   return "";
});*/


Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::group(['middleware' => 'auth:api'], function(){

    Route::get('details', 'API\UserController@details'); //detalhes user
    Route::put('user', 'API\UserController@update');
    Route::post('product', 'API\ProductController@createProduct');

});

Route::get('/products', "API\ProductController@products");
Route::get('/products/{id}', 'API\ProductController@product');
Route::get('/products/{id}/price', 'API\ProductController@productPrice');
Route::get('/products/{id}/name', 'API\ProductController@productName');

