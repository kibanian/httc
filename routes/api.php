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


Route::middleware('auth:api')->group(function(){
    Route::get('/user', 'Api\UserController@index');
    Route::get('/event', 'Api\EventController@index');
    Route::get('/event/{id}', 'Api\EventController@show');

    Route::post('/event', 'Api\EventController@store');

    Route::post('/attend', 'Api\AttendController@store');

    Route::post('/tenniscourt', 'Api\TenniscourtController@store');
    Route::post('/tenniscourtfee', 'Api\TenniscourtFeeController@store');

    Route::post('/fee', 'Api\AttendFeeController@store');
    Route::post('/cancel', 'Api\CancelController@store');
    Route::post('/cardtype', 'Api\CardTypeController@store');
    Route::post('/card', 'Api\CardController@store');
    Route::get('/card/{user_id}', 'Api\CardController@show');
    Route::post('/me', 'Api\LoginController@me');
    Route::post('/logout', 'Api\LoginController@logout');
});

Route::post('/user', 'Api\UserController@store');
Route::post('/login', 'Api\LoginController@login');

