<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\YearMiddleware;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');
});

Route::post("register", 'App\Http\Controllers\UserController@registerUser');

Route::group([
    'middleware' => YearMiddleware::class
], function ($router) {
    Route::get('books/stats', 'App\Http\Controllers\StatsController@getDashStats');
});

Route::group([
    'middleware' => 'api'
], function ($router) {
    Route::get("years", 'App\Http\Controllers\StatsController@getReadingYears');
});


