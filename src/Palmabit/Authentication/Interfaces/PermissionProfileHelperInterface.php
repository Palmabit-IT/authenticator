<?php  namespace Palmabit\Authentication\Interfaces; 
/**
 * Interface PermissionProfileHelperInterface
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */
interface PermissionProfileHelperInterface 
{
    /**
     * Check if the current user has permission to edit the profile
     * @return boolean
     */
    public function checkProfileEditPermission($user_id);
} 