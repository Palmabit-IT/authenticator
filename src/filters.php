<?php
/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| Check that the current user is logged and active
|
*/

Route::filter('logged', function()
{
    $auth = App::make('authenticator');
    if (! $auth->check()) return Redirect::to('landingnew');
});

Route::filter('logged_401', function()
{
    $auth = App::make('authenticator');
    if (! $auth->check()) return App::abort(401);
});

/*
|--------------------------------------------------------------------------
| Permission Filter
|--------------------------------------------------------------------------
|
| Check that the current user is logged in and has a the permission corresponding to the config menu file
|
*/
use Palmabit\Authentication\Helpers\FileRouteHelper;

Route::filter('can_see', function()
{
    $helper = new FileRouteHelper;
    $auth_helper = App::make('authentication_helper');
    $perm = $helper->getPermFromCurrentRoute();

    if( $perm && (! ($auth_helper->hasPermission( $perm ))) ) return Redirect::to('/');
});
