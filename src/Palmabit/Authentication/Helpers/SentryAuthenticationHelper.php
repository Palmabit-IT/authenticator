<?php  namespace Palmabit\Authentication\Helpers; 
/**
 * Class SentryAuthenticationHelper
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
use Illuminate\Support\Facades\Config;
use Palmabit\Authentication\Interfaces\AuthenticationHelperInterface;
use Palmabit\Authentication\Interfaces\PermissionProfileHelperInterface;
use Session;

class SentryAuthenticationHelper implements AuthenticationHelperInterface, PermissionProfileHelperInterface
{
    /**
     * Check if the current user is logged and has access
     * to all the permissions $permissions
     *
     * @param $permissions
     * @return boolean
     */
    public function hasPermission(array $permissions)
    {
        $sentry = \App::make('sentry');
        $current_user = $sentry->getUser();
        if(! $current_user)
            return false;
        if($permissions && (! $current_user->hasAnyAccess($permissions)) )
            return false;

        return true;
    }

    /**
     * Check if the current user has permission to edit the profile
     *
     * @return boolean
     */
    public function checkProfileEditPermission($user_id)
    {
        $current_user_id = \App::make('sentry')->getUser()->id;

        // edit his profile
        if($user_id == $current_user_id) return true;
        // has special permission to edit other user profiles
        $edit_perm = Config::get('authentication::permissions.edit_profile');
        if($this->hasPermission($edit_perm) ) return true;

        return false;
    }
}