<?php  namespace Palmabit\Authentication\Repository\Interfaces;

/**
 * Interface UserProfileRepositoryInterface
 */
interface UserProfileRepositoryInterface
{
    /**
     * Obtain the profile from the user_id
     *
     * @param $user_id
     * @return mixed
     */
    public function getFromUserId($user_id);
}