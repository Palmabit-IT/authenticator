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
    if (! Sentry::check()) return Redirect::to('/user/login');
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
use Palmabit\Authentication\Helpers\SentryAuthenticationHelper as AuthHelper;

Route::filter('can_see', function()
{
    $helper = new FileRouteHelper;
    $perm = $helper->getPermFromCurrentRoute();

    if( $perm && (! (AuthHelper::hasPermission( $perm ))) ) App::abort('401');
});