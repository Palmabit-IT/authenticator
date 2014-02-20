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
| Check that the current user is logged and active
|
*/

