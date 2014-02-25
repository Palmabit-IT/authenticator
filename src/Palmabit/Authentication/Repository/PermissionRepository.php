<?php namespace Palmabit\Authentication\Repository;
/**
 * Class PermissionRepository
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */
use Palmabit\Authentication\Exceptions\PermissionException;
use Palmabit\Library\Repository\EloquentBaseRepository;
use Event;
use Palmabit\Authentication\Repository\SentryGroupRepository as GroupRepo;

class PermissionRepository extends EloquentBaseRepository
{
    protected $model_name = '\Palmabit\Authentication\Models\Permission';
    /**
     * @var \Palmabit\Authentication\Repository\SentryGroupRepository
     */
    protected $group_repo;

    public function __construct($group_repo = null)
    {
        $this->group_repo = $group_repo ? $group_repo : new GroupRepo;

        Event::listen('repository.deleting', '\Palmabit\Authentication\Repository\PermissionRepository@checkIsNotAssociatedToAnyGroup');
    }

    /**
     * @param $obj
     * @throws \Palmabit\Authentication\Exceptions\PermissionException
     */
    public function checkIsNotAssociatedToAnyGroup($obj)
    {
        // obtain all groups
        $all_groups = $this->group_repo->all();
        // spin trough groups to check if any of em has the permission
        foreach ($all_groups as $group) {
            $perm = $group->permissions;
            if(array_key_exists($obj->permission, $perm )) throw new PermissionException;
        }
    }
}