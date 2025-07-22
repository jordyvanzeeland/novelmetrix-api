<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\roleMiddleware;

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
    'middleware' => 'api'
], function ($router) {
    Route::get("books/permonth", 'App\Http\Controllers\StatsController@booksPerGenrePerMonth');
    Route::get("books/genres/count", 'App\Http\Controllers\StatsController@countGenres');
    Route::get("books/ratings", 'App\Http\Controllers\StatsController@countRatings');
    Route::get("books/en", 'App\Http\Controllers\StatsController@countEnBooks');

    Route::get("years", 'App\Http\Controllers\StatsController@getReadingYears');
});


