<?php

/**
 * User login and logout
 */
Route::get('/user/login', "Palmabit\\Authentication\\Controllers\\AuthController@getLogin");
Route::get('/user/logout', "Palmabit\\Authentication\\Controllers\\AuthController@getLogout");
Route::post('/user/login', ["before" => "csrf", "uses" => "Palmabit\\Authentication\\Controllers\\AuthController@postLogin"]);
/**
 * Password recovery
 */
Route::get('/user/change-password', "Palmabit\\Authentication\\Controllers\\AuthController@getChangePassword");
Route::get('/user/recupero-password', "Palmabit\\Authentication\\Controllers\\AuthController@getReminder");
Route::post('/user/change-password', ["before" => "csrf", 'uses' => "Palmabit\\Authentication\\Controllers\\@postChangePassword"]);
Route::post('/user/recupero-password', ["before" => "csrf", 'uses' => "Palmabit\\Authentication\\Controllers\\@postReminder"]);
