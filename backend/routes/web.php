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

use App\Mail\DataTidesMailable;
use Carbon\Carbon;

Auth::routes();

Route::get('/', 'AppController@index')->name('app');
Route::get('/trigger-tides', 'AppController@triggerTidesData');

Route::get('/home', 'HomeController@index')->name('home');
