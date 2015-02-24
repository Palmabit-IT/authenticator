<?php  namespace Palmabit\Authentication\Interfaces;

/**
 * Interface AuthenticationHelperInterface
 */
interface AuthenticationHelperInterface
{
    /**
     * Check if the current user is logged and has the
     * permission name
     *
     * @param $permissions
     * @return boolean
     */
    public function hasPermission(array $permissions);
}