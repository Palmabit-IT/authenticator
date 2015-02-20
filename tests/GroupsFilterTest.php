<?php namespace Palmabit\Authentication\Tests;


use Palmabit\Authentication\Classes\Filters\GroupsFilter;
use Palmabit\Authentication\Helpers\FormHelper;
use Palmabit\Authentication\Interfaces\GroupsFilterInterface;
use Palmabit\Authentication\Repository\SentryGroupRepository;
use Palmabit\Authentication\Tests\traits\CreateGroupTrait;
use Palmabit\Authentication\Tests\traits\CreateUserTrait;

class GroupsFilterTest extends DbTestCase
{
    use CreateGroupTrait, CreateUserTrait;

    /**
     * @test
     */
    public function getAllTest()
    {
        $groupFilter = new GroupsFilter();
        $repo = $this->repository();
        $user = $this->createAdminTrait();
        $admin = $this->createSuperadminTrait();
        $adminGroup = $this->createAdminGroup();
        $userGroup = $this->createUserGroup();
        $repo->addGroup($user->id, $userGroup->id);
        $repo->addGroup($admin->id, $adminGroup->id);
        $groups = $groupFilter->getAll();
        $this->assertEquals($groups[0]->name, $adminGroup->name);
    }

    /**
     * @test
     */
    public function getEditableTest()
    {
        $groupFilter = new GroupsFilter();
        $repo = $this->repository();
        $user = $this->createAdminTrait();
        $admin = $this->createSuperadminTrait();
        $userGroup = $this->createUserGroup();
        $adminGroup = $this->createAdminGroup();
        $repo->addGroup($user->id, $userGroup->id);
        $repo->addGroup($admin->id, $adminGroup->id);
        $groupsEditor = $groupFilter->getEditableGroups();
        $this->assertContains('editable', array_keys($groupsEditor[0]->getPermissions()));

    }

    /**
     * @test
     */
    public function getAssignableGroups()
    {
        $groupFilter = new GroupsFilter();
        $repo = $this->repository();
        $user = $this->createAdminTrait();
        $admin = $this->createSuperadminTrait();
        $this->createSuperadminGroup();
        $adminGroup = $this->createAdminGroup();
        $userGroup = $this->createUserGroup();
        $repo->addGroup($user->id, $userGroup->id);
        $repo->addGroup($admin->id, $adminGroup->id);
        $formHelper = new FormHelper();
        $listGroups = $formHelper->getSelectValuesGroups();
       $assignableGroups = $groupFilter->getAssignableGroups($admin,$listGroups);
        $this->assertFalse(false, array_search('superadmin',$assignableGroups));
    }

}
 