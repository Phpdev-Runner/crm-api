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
	if(Auth::check() === true){
        $userName = Auth::user()->name;
        return "logged in as {$userName}!";
	}
    return view('auth.login');
});

Route::get('/test', function(){
    var_dump(Auth::check());
});

Route::get('logout', function(){
	\Illuminate\Support\Facades\Auth::logout();
	return view('auth.login');
});


Auth::routes();
