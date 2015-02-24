<?php  namespace Palmabit\Authentication\Tests;

/**
 * Test FormHelperTest
 */
use Illuminate\Support\Collection;
use Mockery as m;
use Palmabit\Authentication\Helpers\FormHelper;
use Palmabit\Authentication\Models\Permission;

class FormHelperTest extends TestCase
{

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     * @group 2
     **/
    public function it_create_permissions_array_values()
    {
        $value1 = "desc1";
        $value2 = "desc2";
        $value3 = "desc3";
        $obj1 = new Permission(["description" => $value1, "permission" => "perm1"]);
        $obj2 = new Permission(["description" => $value2, "permission" => "perm2"]);
        $obj3 = new Permission(["description" => $value3, "permission" => "perm3"]);
        $objs = [$obj1, $obj2, $obj3];
        $mock_permission = m::mock('Palmabit\Authentication\Repository\EloquentPermissionRepository');
        $mock_permission->shouldReceive('all')->andReturn(new Collection($objs));

        $helper = new FormHelper($mock_permission);
        $values = $helper->getSelectValuesPermission();

        $this->assertEquals("desc1", $values["_perm1"]);
    }

    /**
     * @test
     **/
    public function it_prepare_sentry_permission()
    {
        $data = ["permissions" => "permission1"];
        $operation = 1;

        $helper = new FormHelper();
        $helper->prepareSentryPermissionInput($data, $operation);
        $this->assertEquals(["permission1" => 1], $data["permissions"]);
    }
}
 