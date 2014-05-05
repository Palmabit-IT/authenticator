<?php namespace Palmabit\Authentication\Repository;
/**
 * Class PermissionRepository
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */
use Palmabit\Authentication\Exceptions\PermissionException;
use Palmabit\Authentication\Models\Permission;
use Palmabit\Library\Repository\EloquentBaseRepository;
use Event;
use Palmabit\Authentication\Repository\SentryGroupRepository as GroupRepo;

class EloquentPermissionRepository extends EloquentBaseRepository
{
    /**
     * @var \Palmabit\Authentication\Repository\SentryGroupRepository
     */
    protected $group_repo;

    public function __construct($group_repo = null)
    {
        $this->group_repo = $group_repo ? $group_repo : new GroupRepo;

        Event::listen('repository.deleting', '\Palmabit\Authentication\Repository\EloquentPermissionRepository@checkIsNotAssociatedToAnyGroup');

        return parent::__construct(new Permission);
    }

    /**
     * @param $obj
     * @throws \Palmabit\Authentication\Exceptions\PermissionException
     */
    public function checkIsNotAssociatedToAnyGroup($obj)
    {
        $all_groups = $this->group_repo->all();
        // spin trough groups to check if any of em has the permission
        foreach ($all_groups as $group) {
            $perm = $group->permissions;
            if($this->permissionIsPresent($obj, $perm)) throw new PermissionException;
        }
    }

    /**
     * @param $obj
     * @param $perm
     * @return bool
     */
    private function permissionIsPresent($obj, $perm)
    {
        return array_key_exists($obj->permission, $perm);
    }
}