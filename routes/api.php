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

// USERS
Route::name('user.index')->get('/users', 'UserController@index');
Route::name('user.show')->get('/user/{id}', 'UserController@show');
Route::name('user.store')->post('/user','UserController@store');
Route::name('user.update')->put('/user/{id}','UserController@update');

Route::name('permission.index')->get('/permissions', 'PermissionsController@index');
Route::name('permission.show')->get('/permission/{id}', 'PermissionsController@show');
Route::name('permission.store')->post('/permission','PermissionsController@store');
Route::name('permission.update')->put('/permission/{id}','PermissionsController@update');

Route::name('role.index')->get('/roles', 'RolesController@index');
Route::name('role.show')->get('/role/{id}', 'RolesController@show');
Route::name('role.showrolepermissions')->get('/rolepermissions/{id}', 'RolesController@showRolePermissions');
Route::name('role.store')->post('/role','RolesController@store');
Route::name('role.update')->put('/role/{id}','RolesController@update');