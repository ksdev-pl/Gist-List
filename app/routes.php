<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

Route::get('/signin', 'UserController@signIn');
Route::get('/getaccesstoken', [
    'before' => 'csrfGithub',
    'uses' => 'UserController@getAccessToken'
]);

Route::get('/ratelimit', 'UserController@getRateLimit');

Route::get('/gists', 'GistController@index');
Route::get('/gists/backup', 'GistController@backup');

Route::get('/signout', 'UserController@signOut');