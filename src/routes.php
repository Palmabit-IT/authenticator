<?php
//////////////////// Authenticazione //////////////////////////
/**
 * User login and logout
 */
Route::get('/user/login', "Palmabit\\Authentication\\Controllers\\AuthController@getLogin");
Route::get('/user/logout', "Palmabit\\Authentication\\Controllers\\AuthController@getLogout");
Route::post('/user/login', ["before" => "csrf", "uses" => "Palmabit\\Authentication\\Controllers\\AuthController@postLogin"]);
/**
 * Password recovery
 */
Route::get('/user/change-password', 'Palmabit\Authentication\Controllers\AuthController@getChangePassword');
Route::get('/user/recupero-password', "Palmabit\\Authentication\\Controllers\\AuthController@getReminder");
Route::post('/user/change-password/', ["before" => "csrf", 'uses' => "Palmabit\\Authentication\\Controllers\\AuthController@postChangePassword"]);
Route::post('/user/recupero-password', ["before" => "csrf", 'uses' => "Palmabit\\Authentication\\Controllers\\AuthController@postReminder"]);

//////////////////// Admin Panel //////////////////////////

Route::group( ['before' => 'logged'], function()
{
    Route::get('/admin/home', ['as' => 'home', function(){
        return View::make('authentication::home.home');
    }]);

    Route::get('/admin/users/list', ['as' => 'users.list', 'uses' => 'Palmabit\Authentication\Controllers\UserController@getList']);
    Route::get('/admin/users/edit', ['as' => 'users.edit', 'uses' => 'Palmabit\Authentication\Controllers\UserController@editUser']);
    Route::post('/admin/users/edit', ["before" => "csrf", 'as' => 'users.edit', 'uses' => 'Palmabit\Authentication\Controllers\UserController@postEditUser']);
    Route::get('/admin/users/delete', ["before" => "csrf", 'as' => 'users.delete', 'uses' => 'Palmabit\Authentication\Controllers\UserController@deleteUser']);
});