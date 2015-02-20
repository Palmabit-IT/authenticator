<?php namespace Palmabit\Authentication\Tests\traits;


use Palmabit\Authentication\Repository\SentryGroupRepository;

trait CreateGroupTrait
{
    public function createAdminGroup()
    {

        $groupRepository = new SentryGroupRepository();
        $inputAdmin = [
            'name' => 'admin',
            'permissions' => [
                'administrator' => 1,
                'editable' => 1
            ],
            'blocked' => 0,
        ];
        return $groupRepository->create($inputAdmin);

    }

    public function createUserGroup()
    {

        $groupRepository = new SentryGroupRepository();
        $inputUser = [
            'name' => 'User',
            'permissions' => [
                'user' => 1,
            ],
            'blocked' => 0,
        ];
        return $groupRepository->create($inputUser);

    }

    public function createSuperadminGroup()
    {

        $groupRepository = new SentryGroupRepository();
        $inputSuperadmin = [
            'name' => 'superadmin',
            'permissions' => [
                'administrator',
                'user' => 1,
                'editable' => 1
            ],
            'blocked' => 0,
        ];
        return $groupRepository->create($inputSuperadmin);

    }
} 