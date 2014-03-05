<?php  namespace Palmabit\Authentication\Repository\Interfaces; 
/**
 * Class UserGroupRepositoryInterface
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */
interface UserGroupRepositoryInterface
{
    /**
     * Obtain a group from a given name
     * @param $name
     * @return mixed
     */
    public function findByName($name);
} 