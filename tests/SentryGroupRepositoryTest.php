<?php  namespace Palmabit\Authentication\Tests;

/**
 * Test GroupRepositoryTest

 */
use App;
use Palmabit\Authentication\Repository\SentryGroupRepository;
use Mockery as m;

class SentryGroupRepositoryTest extends DbTestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->r = new SentryGroupRepository;
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     **/
    public function it_creates_a_group()
    {
        $group = $this->r->create(array(
            'name' => 'Users',
            'permissions' => array(
                'admin' => 1,
                'users' => 1,
            ),
        ));

        $this->assertEquals("Users", $group->name);
    }

    /**
     * @test
     **/
    public function it_find_group()
    {
        $group = $this->r->create(array(
            'name' => 'Users',
            'permissions' => array(
                'admin' => 1,
                'users' => 1,
            ),
            "blocked" => 0,
        ));
        $group_find = $this->r->find($group->id);

        $this->assertEquals($group_find->toArray(), $group->toArray());
    }

    /**
     * @test
     * @expectedException \Palmabit\Authentication\Exceptions\GroupNotFoundException
     **/
    public function it_find_throws_exception()
    {
        $group_find = $this->r->find(20);
    }

    /**
     * @test
     **/
    public function it_return_all_models()
    {
        $group = $this->r->create(array(
            'name' => 'Users',
            'permissions' => array(
                'admin' => 1,
                'users' => 1,
            ),
        ));

        $all = $this->r->all();
        $this->assertEquals(1, count($all));
    }

    /**
     * @test
     **/
    public function it_delete_a_group()
    {
        $group = $this->r->create(array(
            'name' => 'Users',
            'permissions' => array(
                'admin' => 1,
                'users' => 1,
            ),
            'blocked' => 0,
        ));

        $success = $this->r->delete($group->id);
        $this->assertTrue($success);
    }


    /**
     * @test
     **/
    public function it_update_a_group()
    {
        $group = $this->r->create(array(
            'name' => 'Users',
            'permissions' => array(
                'admin' => 1,
                'users' => 1,
            ),
            "blocked" => 0,
        ));
        $newname = ["name" => "new name"];
        $this->r->update($group->id, $newname);

        $group_find = $this->r->find($group->id);
        $this->assertEquals($newname["name"], $group_find->name);
    }

    /**
     * @test
     **/
    public function it_find_group_by_name()
    {
        $expected_group = $this->r->create([
            "name" => "name",
            "description" => "name"
        ]);
        $mock_sentry = m::mock('StdClass')->shouldReceive('findGroupByName')->once()->andReturn($expected_group)->getMock();
        App::instance('sentry', $mock_sentry);
        $name = "name";
        $repo = new SentryGroupRepository();
        $group = $repo->findByName($name);

        $this->assertEquals($expected_group->name, $group->name);
    }
}
 