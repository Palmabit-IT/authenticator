<?php
//////////////////// Registration //////////////////////////

Route::get('/user/signup', ['as' => 'users.signup', 'uses' => 'Palmabit\Authentication\Controllers\UserController@getSignupUser']);
Route::post('/user/signup', ['before' => 'csrf', 'as' => 'users.signup', 'uses' => 'Palmabit\Authentication\Controllers\UserController@postSignupUser']);

//////////////////// Authentication //////////////////////////
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
Route::get('/user/recupero-password-conferma', function(){
    return View::make('authentication::auth.confirmation');
});
Route::get('/user/recupero-password', "Palmabit\\Authentication\\Controllers\\AuthController@getReminder");
Route::post('/user/change-password/', ["before" => "csrf", 'uses' => "Palmabit\\Authentication\\Controllers\\AuthController@postChangePassword"]);
Route::post('/user/recupero-password', ["before" => "csrf", 'uses' => "Palmabit\\Authentication\\Controllers\\AuthController@postReminder"]);


//////////////////// Admin Panel //////////////////////////

Route::group( ['before' => ['logged', 'can_see']], function()
{
    Route::get('/admin/home', ['as' => 'home', function(){
        return View::make('authentication::home.home');
    }]);

    // user
    Route::get('/admin/users/list', ['as' => 'users.list', 'uses' => 'Palmabit\Authentication\Controllers\UserController@getList']);
    Route::get('/admin/users/edit', ['as' => 'users.edit', 'uses' => 'Palmabit\Authentication\Controllers\UserController@editUser']);
    Route::post('/admin/users/edit', ["before" => "csrf", 'as' => 'users.edit', 'uses' => 'Palmabit\Authentication\Controllers\UserController@postEditUser']);
    Route::get('/admin/users/delete', ["before" => "csrf", 'as' => 'users.delete', 'uses' => 'Palmabit\Authentication\Controllers\UserController@deleteUser']);
    Route::get('/admin/users/profile/edit', ['as' => 'users.profile.edit', 'uses' => 'Palmabit\Authentication\Controllers\UserController@editProfile']);
    Route::post('/admin/users/profile/edit', ['before' => 'csrf', 'as' => 'users.profile.edit', 'uses' => 'Palmabit\Authentication\Controllers\UserController@postEditProfile']);
    Route::get('/admin/users/import', ['as' => 'users.import', 'uses' => 'Palmabit\Authentication\Controllers\UserController@import']);
    Route::post('/admin/users/import', ['as' => 'users.import', 'uses' => 'Palmabit\Authentication\Controllers\UserController@postImport']);

    // groups
    Route::any('/admin/users/groups/delete', ["before" => "csrf", 'as' => 'users.groups.delete', 'uses' => 'Palmabit\Authentication\Controllers\UserController@deleteGroup']);
    Route::post('/admin/users/groups/add', ["before" => "csrf", 'as' => 'users.groups.add', 'uses' => 'Palmabit\Authentication\Controllers\UserController@addGroup']);
    Route::get('/admin/groups/list', ['as' => 'users.groups.list', 'uses' => 'Palmabit\Authentication\Controllers\GroupController@getList']);
    Route::get('/admin/groups/edit', ['as' => 'users.groups.edit', 'uses' => 'Palmabit\Authentication\Controllers\GroupController@editGroup']);
    Route::post('/admin/groups/edit', ["before" => "csrf", 'as' => 'users.groups.edit', 'uses' => 'Palmabit\Authentication\Controllers\GroupController@postEditGroup']);
    Route::get('/admin/groups/delete', ["before" => "csrf", 'as' => 'users.groups.delete', 'uses' => 'Palmabit\Authentication\Controllers\GroupController@deleteGroup']);
    Route::any('/admin/groups/editpermission', ["before" => "csrf", 'as' => 'users.groups.edit.permission', 'uses' => 'Palmabit\Authentication\Controllers\GroupController@editPermission']);

    // permissions
    Route::get('/admin/permissions/list', ['as' => 'users.permission.list', 'uses' => 'Palmabit\Authentication\Controllers\PermissionController@getList']);
    Route::get('/admin/permissions/edit', ['as' => 'users.permission.edit', 'uses' => 'Palmabit\Authentication\Controllers\PermissionController@editPermission']);
    Route::post('/admin/permissions/edit', ["before" => "csrf", 'as' => 'users.permission.edit', 'uses' => 'Palmabit\Authentication\Controllers\PermissionController@postEditPermission']);
    Route::get('/admin/permissions/delete', ["before" => "csrf", 'as' => 'users.permission.delete', 'uses' => 'Palmabit\Authentication\Controllers\PermissionController@deletePermission']);

});