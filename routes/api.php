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

Route::get('open', 'DataController@open');
Route::post('register', 'TbuserController@register');

Route::group(['middleware' => ['decrypt.data']], function() {
    Route::post('auth/login', 'TbuserController@authenticate');
});

// Route::group(['middleware' => ['jwt.verify']], function() {
//     Route::get('user', 'TbuserController@getAuthenticatedUser');
//     Route::get('closed', 'DataController@closed');
// });

Route::group(['middleware' => ['jwt.auth','decrypt.data']], function() {
    Route::get('auth/user', 'TbuserController@getAuthenticatedUser');
    Route::post('auth/logout', 'TbuserController@logout');

    Route::get('loadUser', 'TbuserController@loadUser');
    Route::post('saveUser', 'TbuserController@saveUser');
    Route::post('changePass', 'TbuserController@changePass');
    Route::post('resetPass', 'TbuserController@resetPass');

    Route::get('loadIuran', 'TbiranController@loadIuran');
    Route::get('loadSettle', 'TbiranController@loadSettle');
    Route::post('saveIuran', 'TbiranController@saveIuran');

    Route::get('loadRecord', 'TbiranController@loadRecord');
});

Route::group(['middleware' => 'jwt.refresh','decrypt.data'], function(){
    Route::get('auth/refresh', 'TbuserController@refresh');
});