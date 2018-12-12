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
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', 'HomeController@index')->name('home');

    Route::group(['prefix' => 'comment'], function () {
        Route::resource('/add', 'Comment\CommentController');
        Route::post('/update/{id}', 'Comment\CommentController@update');
        Route::delete('/delete/{id}', 'Comment\CommentController@destroy');
        Route::resource('/rating/add', 'Comment\RatingController');
        Route::get('/replies/show/{id}', 'Comment\CommentController@show');
    });
});


