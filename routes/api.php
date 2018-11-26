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

Route::post('register', 'TbuserController@register');
Route::post('auth/login', 'TbuserController@authenticate');
Route::get('open', 'DataController@open');

// Route::group(['middleware' => ['jwt.verify']], function() {
//     Route::get('user', 'TbuserController@getAuthenticatedUser');
//     Route::get('closed', 'DataController@closed');
// });

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('auth/user', 'TbuserController@getAuthenticatedUser');
    Route::post('auth/logout', 'TbuserController@logout');
    Route::get('loadUser', 'TbuserController@loadUser');
});

Route::group(['middleware' => 'jwt.refresh'], function(){
    Route::get('auth/refresh', 'TbuserController@refresh');
});