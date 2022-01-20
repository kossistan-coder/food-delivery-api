<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' =>['auth:sanctum']],function(){
    Route::get('users','UserController@index');
    Route::post('users/{id}/paniers','UserController@store');
    
    Route::post('/logout','AuthController@logout');
    Route::get('/list','AuthController@show');


    //administrateurs
    Route::delete('/admin/{id}','AuthController@destroy');
    Route::get('/admin/users','AdminController@show');
    Route::put('/admin/{id}','AuthController@update');
    Route::get('admin','AdminController@index');

    // post 
    Route::get('/admin/{id}/post','AdminController@PostByAdmin');
    Route::get('/admin/{id}/limit/{nb}','AdminController@limit');
    Route::post('/admin/{id}/post','AdminController@store');
    Route::put('/admin/{id}/post/{nb}','AdminController@update');
    Route::delete('/admin/{id}/post/{nb}','AdminController@destroy');
});

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::post('/admin', 'AuthController@admin');
Route::get('/posts','PostController@index');
