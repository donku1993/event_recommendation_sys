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



Route::get('/', 'HomeController@index');
Route::get('/event/info', 'HomeController@eventInfo');
Route::get('/event/create', 'HomeController@eventCreate');
Route::get('/auth/info', 'HomeController@userInfo');

Auth::routes();


