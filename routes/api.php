<?php

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

Route::group(['prefix' => 'v1'], function () {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
});

Route::group(['middleware' => ['auth:api'], 'prefix' => 'v1'], function () use ($router) {
    Route::get('/user', 'AuthController@details');
    Route::put('/user', 'AuthController@update');
    Route::post('/logout', 'AuthController@logout');
});
