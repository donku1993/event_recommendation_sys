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
Auth::routes();
// Home
Route::get('/', 'HomeController@index');
// User
Route::get('/user/{id}', 'UserController@show');
Route::get('/user/{id}/edit', 'UserController@edit');
Route::put('/user/{id}', 'UserController@update');
// Event
Route::get('/event', 'EventController@index');
Route::get('/event/create', 'EventController@create');
Route::get('/event/{id}', 'EventController@show');
Route::get('/event/{id}/member', 'EventController@showMember');
Route::get('/event/{id}/edit', 'EventController@edit');
Route::post('/event', 'EventController@store');
Route::put('/event/{id}', 'EventController@update');
Route::get('/event/{id}/mark', 'EventController@mark');
Route::put('/event/{id}/join', 'EventController@join');
Route::delete('/event/{id}', 'EventController@destroy');
// Group
Route::get('/group', 'GroupController@index');
Route::get('/group/create', 'GroupController@create');
Route::get('/group/{id}', 'GroupController@show');
Route::get('/group/{id}/edit', 'GroupController@edit');
Route::post('/group', 'GroupController@store');
Route::put('/group/{id}', 'GroupController@update');
Route::put('/group/{id}/mark', 'GroupController@mark');
Route::delete('/group/{id}', 'GroupController@destroy');
// Group Form
Route::get('/group_form', 'GroupFormController@index');
Route::get('/group_form/{id}', 'GroupFormController@show');
Route::put('/group_form/{id}/approve', 'GroupFormController@approve');
Route::put('/group_form/{id}/reject', 'GroupFormController@reject');