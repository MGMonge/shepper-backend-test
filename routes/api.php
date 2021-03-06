<?php

use Illuminate\Http\Request;
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

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', ['uses' => 'UserController@show', 'as' => 'users.show']);

    Route::get('/locations', ['uses' => 'LocationController@index', 'as' => 'locations.index']);
    Route::post('/locations', ['uses' => 'LocationController@store', 'as' => 'locations.store']);
    Route::put('/locations/{location}', ['uses' => 'LocationController@update', 'as' => 'locations.update']);
    Route::delete('/locations/{location}', ['uses' => 'LocationController@destroy', 'as' => 'locations.destroy']);
});
