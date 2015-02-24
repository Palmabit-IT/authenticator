<?php namespace Palmabit\Authentication\Repository\Interfaces;

/**
 * Interface UserRepositoryInterface
 */
interface UserRepositoryInterface
{

    /**
     * Activates a user
     *
     * @param integer id
     * @return mixed
     */
    public function activate($id);

    /**
     * Deactivate a user
     *
     * @param $id
     * @return mixed
     */
    public function deactivate($id);

    /**
     * Suspends a user
     *
     * @param $id
     * @param $duration in minutes
     * @return mixed
     */
    public function suspend($id, $duration);

    /**
     * @param $group_id
     * @param $user_id
     * @return mixed
     */
    public function addGroup($user_id, $group_id);

    /**
     * @param $group_id
     * @param $user_id
     * @return mixed
     */
    public function removeGroup($user_id, $group_id);

    /**
     * Obtain a list of user from a given group
     *
     * @param String $group_name
     * @return mixed
     */
    public function findFromGroupName($group_name);
}