<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\YearMiddleware;

Route::post("register", 'App\Http\Controllers\UserController@registerUser');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'books'
], function ($router) {
    Route::get('get', 'App\Http\Controllers\BooksController@getUserBooks');
    Route::get('current', 'App\Http\Controllers\BooksController@getCurrentReadingBookOfUser');
    Route::post('insert', 'App\Http\Controllers\BooksController@insertBook');
    Route::get('get/{id}', 'App\Http\Controllers\BooksController@getBook');
    Route::put('get/{id}/update', 'App\Http\Controllers\BooksController@updateBook');
    Route::delete('get/{id}/delete', 'App\Http\Controllers\BooksController@deleteBook');
});

Route::group([
    'middleware' => ['api', YearMiddleware::class]
], function ($router) {
    Route::get('books/stats', 'App\Http\Controllers\StatsController@getDashStats');
});

Route::group([
    'middleware' => 'api'
], function ($router) {
    Route::get("years", 'App\Http\Controllers\StatsController@getReadingYears');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'write'
], function ($router) {
    Route::get('stories', 'App\Http\Controllers\Write\StoriesController@getStories');
    Route::get('stories/{id}', 'App\Http\Controllers\Write\StoriesController@getStoryByID');
    Route::post('stories/insert', 'App\Http\Controllers\Write\StoriesController@insertStory');
    Route::put('stories/{id}/update', 'App\Http\Controllers\Write\StoriesController@updateStory');
    Route::delete('stories/{id}/delete', 'App\Http\Controllers\Write\StoriesController@deleteStory');
});


