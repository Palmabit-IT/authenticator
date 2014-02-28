<?php  namespace Palmabit\Authentication\Helpers; 
/**
 * Class SentryAuthenticationHelper
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
use Illuminate\Support\Facades\Config;
use Palmabit\Authentication\Interfaces\AuthenticationHelperInterface;
use Palmabit\Authentication\Interfaces\PermissionProfileHelperInterface;
use Session, App;

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
        $sentry = App::make('sentry');
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
        $current_user_id = App::make('sentry')->getUser()->id;

        // edit his profile
        if($user_id == $current_user_id) return true;
        // has special permission to edit other user profiles
        $edit_perm = Config::get('authentication::permissions.edit_profile');
        if($this->hasPermission($edit_perm) ) return true;

        return false;
    }

    /**
     * Obtain the user that needs to be notificated on registration
     *
     * @return array
     */
    public function getNotificationRegistrationUsersEmail()
    {
        $group_name = Config::get('authentication::permissions.profile_notification_group');
        $user_r = App::make('user_repository');
        $users = $user_r->findFromGroupName($group_name);

        return $users;
    }

}