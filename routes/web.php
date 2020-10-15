<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index');
Route::get('/twitter/{username}', 'TwitterController@getTweet');
Route::post('/twitter/{username}', 'TwitterController@save_tweets');
Route::get('/twitter', 'TwitterController@index');
Route::post('/twitter', 'TwitterController@index');

Route::get('/tweet/{username}', 'TweetController@show');
Route::get('/tweet/{id}/getSentiment', 'TweetController@getSentiment');
Route::get('/tweet/{id}/{idTweet}', 'TweetController@detail');
Route::get('/tweet', 'TweetController@index');
Route::post('/tweet', 'TweetController@index');

Route::get('/slangword', 'SlangwordController@index');
Route::get('/slangword/tambah', 'SlangwordController@view_save');
Route::post('/slangword/tambah', 'SlangwordController@view_save');
Route::get('/slangword/{id}/edit', 'SlangwordController@view_edit');
Route::post('/slangword/{id}/edit', 'SlangwordController@view_edit');
Route::get('/slangword/{id}/delete', 'SlangwordController@delete');

Route::get('/stopword', 'StopwordController@index');
Route::get('/stopword/tambah', 'StopwordController@view_save');
Route::post('/stopword/tambah', 'StopwordController@view_save');
Route::get('/stopword/{id}/edit', 'StopwordController@view_edit');
Route::post('/stopword/{id}/edit', 'StopwordController@view_edit');
Route::get('/stopword/{id}/delete', 'StopwordController@delete');

Route::get('/preprocessing', 'PreprocessingController@index');
Route::post('/preprocessing', 'PreprocessingController@index');
Route::get('/preprocessing/{id}', 'PreprocessingController@show');
Route::post('/preprocessing/{id}/{idTweet}/label', 'PreprocessingController@changeLabel');
Route::get('/preprocessing/{id}/processed', 'PreprocessingController@store');

Route::get('/confmatrix', 'ConfussionMatrixController@index');
Route::post('/confmatrix', 'ConfussionMatrixController@index');
Route::get('/confmatrix/{id}', 'ConfussionMatrixController@show');
