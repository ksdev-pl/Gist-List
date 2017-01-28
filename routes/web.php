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

Route::get('/', 'PagesController@home');

Route::get('/auth/github', 'Auth\GithubAuthController@redirectToProvider')->name('auth.redirect');
Route::get('/auth/github/callback', 'Auth\GithubAuthController@handleProviderCallback');

Route::get('/logout', 'Auth\GithubAuthController@logout')->name('auth.logout');

Route::group(['middleware' => 'auth'], function () {

    Route::get('gists', 'GistsController@index')->name('gists.index');

});
