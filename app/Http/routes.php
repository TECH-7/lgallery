<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    //return view('welcome');
    return redirect('albums');
});

Route::auth();

Route::post('photos/massmove', 'AlbumsController@massMove');
Route::post('albums/{albums}/upload', 'AlbumsController@upload');
Route::resource('albums', 'AlbumsController');

Route::get('photos/tag/{tag}', 'PhotosController@tag');
Route::get('photos/{photos}/comment', 'PhotosController@createComment');
Route::post('photos/{photos}/comment', 'PhotosController@storeComment');
Route::delete('photos/massdelete', 'PhotosController@massDestroy');
Route::resource('photos', 'PhotosController');
/*
Route::get('photos/{photos}/edit', 'PhotosController@edit');
Route::patch('photos/{photos}', 'PhotosController@update');
Route::delete('photos/{photos}', 'PhotosController@destroy');
Route::get('photos', 'PhotosController@index');
*/

Route::get('/home', 'HomeController@index');
