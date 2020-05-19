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

Route::get('profiles', 'Api\ProfilesController@allProfiles');
Route::get('profiles/edit/{id}', 'Api\ProfilesController@editProfile');
Route::post('profiles/edit/update-profile', 'Api\ProfilesController@updateProfile');
Route::post('profiles/new', 'Api\ProfilesController@createProfile');
Route::post('profiles/delete', 'Api\ProfilesController@deleteProfile');
