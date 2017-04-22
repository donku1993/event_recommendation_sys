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

Route::group(['middleware' => 'browser_checker'], function() {
	Auth::routes();

	// Home
	Route::get('/', 'HomeController@index');



	// User
	Route::get('/user/{id}', 'UserController@show')->name('user.info');
	Route::get('/user/{id}/edit', 'UserController@edit')->name('user.edit');

	Route::put('/user/{id}', 'UserController@update')->name('user.update');
	Route::post('/user/{id}/icon_update', 'UserController@icon_update')->name('user.icon_update');



	// Event
	Route::get('/event', 'EventController@index')->name('event.list');
	Route::get('/event/create', 'EventController@create')->name('event.create');
	Route::get('/event/{id}', 'EventController@show')->name('event.info');
	Route::get('/event/{id}/member', 'EventController@showMember')->name('event.member');
	Route::get('/event/{id}/edit', 'EventController@edit')->name('event.edit');

	Route::post('/event', 'EventController@store')->name('event.store');
	Route::put('/event/{id}', 'EventController@update')->name('event.update');
	Route::put('/event/{id}/mark', 'EventController@mark')->name('event.mark');
	Route::put('/event/{id}/join', 'EventController@join')->name('event.join');
	Route::put('/event/{id}/evaluation', 'EventController@evaluation')->name('event.evaluation');
	Route::put('/event/{id}/cover_update', 'EventController@cover_update')->name('event.cover_update');

	Route::delete('/event/{id}', 'EventController@destroy')->name('event.delete');



	// Group
	Route::get('/group', 'GroupController@index')->name('group.list');
	Route::get('/group/create', 'GroupController@create')->name('group.create');
	Route::get('/group/{id}', 'GroupController@show')->name('group.info');
	Route::get('/group/{id}/edit', 'GroupController@edit')->name('group.edit');

	Route::post('/group', 'GroupController@store')->name('group.store');
	Route::put('/group/{id}', 'GroupController@update')->name('group.update');
	Route::put('/group/{id}/mark', 'GroupController@mark')->name('group.mark');
	Route::put('/group/{id}/icon_update', 'GroupController@icon_update')->name('group.icon_update');

	Route::delete('/group/{id}', 'GroupController@destroy')->name('group.delete');



	// Group Form
	Route::get('/group_form', 'GroupFormController@index')->name('group_form.list');
	Route::get('/group_form/{id}', 'GroupFormController@show')->name('group_form.info');

	Route::put('/group_form/{id}/approve', 'GroupFormController@approve')->name('group_form.approve');
	Route::put('/group_form/{id}/reject', 'GroupFormController@reject')->name('group_form.reject');
	Route::put('/group_form/{id}/read', 'GroupFormController@form_read')->name('group_form.read');



	// Participant
	Route::put('/participant/{event_id}/evaluation', 'ParticipantController@evaluation')->name('participant.evaluation');



	// Record
	Route::get('/record/group/{id}', 'RecordController@groupRecord')->name('record.group');
	Route::get('/record/event/{id}', 'RecordController@eventRecord')->name('record.event');
});


Route::get('/newest_events', 'HomeController@newest_events');
Route::get('/most_popular_events', 'HomeController@most_popular_events');
Route::get('/recommend_events', 'HomeController@recommend_events');


Route::get('/test', 'EventController@calculate_all_similarity');
