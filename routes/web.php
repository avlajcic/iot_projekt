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

Route::post('/time-settings', 'HomeController@timeSettings')->name('timeSettings');
Route::post('/brightness-settings', 'HomeController@brightnessSettings')->name('brightnessSettings');
Route::post('/room-name-settings', 'HomeController@roomNameSettings')->name('roomNameSettings');
