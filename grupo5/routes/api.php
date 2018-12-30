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
   return "CARALHO";
});*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});/*

/*Route::group(['prefix' => 'api'], function () {
    dd("chegou aki");
    Route::group(['prefix' => 'user'], function ()
    {
        Route::get('{id}', function($id){
            return "TESTE";
        });
    });
});*/

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::group(['middleware' => 'auth:api'], function(){

    Route::get('details', 'API\UserController@details');

});


Route::get('/products/', "Api@products");

Route::get('/products/{id}', 'Api@product');
Route::get('/products/{id}/price', 'Api@productPrice');
Route::get('/products/{id}/name', 'Api@productName');

