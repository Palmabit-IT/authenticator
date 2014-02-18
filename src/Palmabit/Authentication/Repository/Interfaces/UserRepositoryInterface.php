<?php namespace Classes\Repository\Interfaces;
/**
 * Interface UserRepositoryInterface
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */
interface UserRepositoryInterface 
{

    /**
     * Activates a user
     * @param integer id
     * @return mixed
     */
    public function activate($id);

    /**
     * Deactivate a user
     * @param $id
     * @return mixed
     */
    public function deactivate($id);

    /**
     * Suspends a user
     * @param $id
     * @param $duration in minutes
     * @return mixed
     */
    public function suspend($id, $duration);
}