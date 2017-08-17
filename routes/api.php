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

Route::group(['prefix'=>'v1'], function(){
	Route::get('view-managers','UsersController@viewManagers');
	Route::post('store-manager','UsersController@storeManager');
	Route::get('edit-manager/{id}','UsersController@editManager');
	Route::post('update-manager/{id}','UsersController@updateManager');
	Route::post('delete-manager/{id}', 'UsersController@deleteManager');
	
	Route::post('invite-manager','UsersController@inviteManager');
	
	Route::get('view-leads','LeadsController@viewLeads');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

