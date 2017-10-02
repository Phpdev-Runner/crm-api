<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

Route::group(['prefix'=>'v1'], function(){

    // LOGIN
    Route::get('/login', function () {
        return view('auth.login');
    });

    Auth::routes();

	// MANAGERS
	Route::get('view-managers','UsersController@viewManagers');
	Route::get('user-empty-form-show', 'UsersController@userEmptyFormShow');
	Route::post('store-manager','UsersController@storeManager');
	Route::get('edit-manager/{id}','UsersController@editManager');
	Route::post('update-manager/{id}','UsersController@updateManager');
	Route::get('delete-manager/{id}', 'UsersController@deleteManager');

	// MANAGER'S ACCOUNT SETTING
	Route::get('set-new-password/{token}','UsersController@setNewPassword');
	Route::post('store-new-password','UsersController@storeNewPassword')->name('store-new-password');
	
	// LEADS
	Route::get('view-leads','LeadsController@viewLeads');
	Route::get('lead-empty-form-show','LeadsController@leadEmptyFormShow');
	Route::post('store-lead','LeadsController@storeLead');
	Route::get('edit-lead/{id}', 'LeadsController@editLead');
	Route::post('update-lead/{id}', 'LeadsController@updateLead');
	Route::get('delete-lead/{id}', 'LeadsController@deleteLead');

	// COMMENTS
    Route::post('store-comment', 'CommentsController@storeComment');
    Route::get('edit-comment/{id}', 'CommentsController@editComment');
    Route::post('update-comment/{id}', 'CommentsController@updateComment');
    Route::get('delete-comment/{id}', 'CommentsController@deleteComment');

    // COMMUNICATION RECORDS
    Route::get('com-record-empty-form-show','CommunicationRecordsController@comRecordEmptyFormShow');
    Route::post('store-com-record','CommunicationRecordsController@storeComRecord');
    Route::get('edit-com-record/{id}','CommunicationRecordsController@editComRecord');
    Route::post('update-com-record/{id}','CommunicationRecordsController@updateComRecord');
    Route::get('delete-com-record/{id}','CommunicationRecordsController@deleteComRecord');

	//test
    Route::get('test',function(){
        $test = 'test';
        dd(route('store-new-password'));
    });

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

