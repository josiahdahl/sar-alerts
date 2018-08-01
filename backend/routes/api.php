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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->group(function () {
    Route::prefix('v1')->group(function () {
        // TODO: Make all of these inherit a base ApiController and standarize responses

        Route::get('weather/{id}', 'WeatherController@get');
        Route::get('tides/{id}', 'TidesController@get');
        Route::get('notices/{locationId}', 'NoticesController@get');
        Route::get('time/{id}', 'TimeController@get');

        Route::prefix('locations')->group(function () {
            Route::get('{id}/weather', 'WeatherController@get');
            Route::get('{id}/tides', 'TidesController@get');
            Route::get('{locationId}/notices', 'NoticesController@get');
            Route::get('{id}/time', 'TimeController@get');
        });

        Route::get('stations/{id}/notices', 'StationNoticeController@view');
        Route::get('stations/{id}/tides', 'StationTidesController@view');
    });
});

