<?php  namespace Palmabit\Authentication\Tests;

/**
 * Test PermissionRepositoryTest

 */
use Mockery as m;
use Palmabit\Authentication\Models\Permission;
use Palmabit\Authentication\Repository\EloquentPermissionRepository as PermissionRepository;

class PermissionRepositoryTest extends TestCase
{

    /**
     * @test
     * @expectedException \Palmabit\Authentication\Exceptions\PermissionException
     **/
    public function it_check_for_groups_and_throws_exception()
    {
        $data_obj = new \StdClass;
        $data_obj->permissions = ["_perm" => "1"];
        $data_stub = [$data_obj];
        $mock_repo_grp = m::mock('Palmabit\Authentication\Repository\GroupRepository')->shouldReceive('all')->andReturn($data_stub)->getMock();
        $perm_repo = new PermissionRepository($mock_repo_grp);
        $permission_obj = new Permission(["description" => "desc", "permission" => "_perm"]);
        $perm_repo->checkIsNotAssociatedToAnyGroup($permission_obj);
    }

    /**
     * @test
     **/
    public function it_check_for_groups_and_does_nothing()
    {
        $data_obj = new \StdClass;
        $data_obj->permissions = ["_perm" => "1"];
        $data_stub = [$data_obj];
        $mock_repo_grp = m::mock('Palmabit\Authentication\Repository\GroupRepository')->shouldReceive('all')->andReturn($data_stub)->getMock();
        $perm_repo = new PermissionRepository($mock_repo_grp);
        $permission_obj = new Permission(["description" => "desc", "permission" => "_perm_false"]);
        $perm_repo->checkIsNotAssociatedToAnyGroup($permission_obj);
    }

    /**
     * @test
     * @expectedException \Palmabit\Authentication\Exceptions\PermissionException
     */
    public function isNotModificablePermissionSuperadmin()
    {
        $data_obj = new \StdClass;
        $data_obj->permissions = ["_perm" => "1"];
        $data_stub = [$data_obj];
        $mock_repo_grp = m::mock('Palmabit\Authentication\Repository\GroupRepository')->shouldReceive('all')->andReturn($data_stub)->getMock();
        $perm_repo = new PermissionRepository($mock_repo_grp);
        $superadmin_obj = new Permission(["description" => "superadmin", "permission" => "_superadmin", "blocked" => 1]);
        $perm_repo->checkIsNotSuperadminOrAdmin($superadmin_obj);
    }

    /**
     * @test
     * @expectedException \Palmabit\Authentication\Exceptions\PermissionException
     */
    public function isNotModificablePermissionadmin()
    {
        $data_obj = new \StdClass;
        $data_obj->permissions = ["_perm" => "1"];
        $data_stub = [$data_obj];
        $mock_repo_grp = m::mock('Palmabit\Authentication\Repository\GroupRepository')->shouldReceive('all')->andReturn($data_stub)->getMock();
        $perm_repo = new PermissionRepository($mock_repo_grp);
        $admin_obj = new Permission(["description" => "admin", "permission" => "_admin", "blocked" => 1]);
        $perm_repo->checkIsNotSuperadminOrAdmin($admin_obj);
    }

    /**
     * @test
     */
    public function isModificablePermissionGuest()
    {
        $data_obj = new \StdClass;
        $data_obj->permissions = ["_perm" => "1"];
        $data_stub = [$data_obj];
        $mock_repo_grp = m::mock('Palmabit\Authentication\Repository\GroupRepository')->shouldReceive('all')->andReturn($data_stub)->getMock();
        $perm_repo = new PermissionRepository($mock_repo_grp);
        $guest_obj = new Permission(["description" => "guest", "permission" => "_guest"]);
        $perm_repo->checkIsNotSuperadminOrAdmin($guest_obj);
    }
}
