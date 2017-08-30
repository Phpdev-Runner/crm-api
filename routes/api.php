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
        if(Auth::check() === true){
            return "logged in";
        }
        return view('auth.login');
    });

    Auth::routes();

	// MANAGERS
	Route::get('view-managers','UsersController@viewManagers');
	Route::get('user-empty-form-show', 'UsersController@userEmptyFormShow');
	Route::post('store-manager','UsersController@storeManager');
	Route::get('edit-manager/{id}','UsersController@editManager');
	Route::post('update-manager/{id}','UsersController@updateManager');
	Route::post('delete-manager/{id}', 'UsersController@deleteManager');
	
	Route::post('invite-manager','UsersController@inviteManager');
	
	// LEADS
	Route::get('view-leads','LeadsController@viewLeads');
	Route::get('lead-empty-form-show','LeadsController@leadEmptyFormShow');
	Route::post('store-lead','LeadsController@storeLead');


	//test
    Route::get('test',function(){
        var_dump(Auth::check());
    });

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

