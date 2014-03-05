<?php
use Mockery as m;
use Palmabit\Authentication\Classes\SentryAuthenticator;
    use Palmabit\Authentication\Models\Group;
    use Palmabit\Authentication\Tests\TestCase;

class SentryAuthenticatorTest extends TestCase {

    public function tearDown()
    {
        m::close();
    }

	public function testGetUserWorks()
	{
        $mock_sentry = m::mock('StdClass')->shouldReceive('findUserByLogin')->andReturn(true)->getMock();
        App::instance('sentry', $mock_sentry);

        $auth = new SentryAuthenticator();
        $success = $auth->getUser("");
        $this->assertTrue($success);
	}

    /**
     * @expectedException Palmabit\Authentication\Exceptions\UserNotFoundException
     */
    public function testGetUserWorksThrowsException()
	{
        $mock_sentry = m::mock('StdClass')->shouldReceive('findUserByLogin')->andThrow(new \Cartalyst\Sentry\Users\UserNotFoundException)->getMock();
        App::instance('sentry', $mock_sentry);

        $auth = new SentryAuthenticator();
        $success = $auth->getUser("");
	}

    public function testGetTokenWorks()
    {
        $mock_user = m::mock('StdClass')->shouldReceive('getResetPasswordCode')->andReturn(true)->getMock();
        $mock_auth = m::mock('Palmabit\Authentication\Classes\SentryAuthenticator')->makePartial();
        $mock_auth->shouldReceive('getUser')->andReturn($mock_user);

        $token = $mock_auth->getToken("");
        $this->assertEquals(true, $token);
    }

    /**
     * @test
     **/
    public function it_gets_user_groups()
    {
        $mock_groups = m::mock('StdClass')->shouldReceive('getGroups')->andReturn([new Group,new Group])->getMock();
        $mock_sentry = m::mock('StdClass')->shouldReceive('getUser')->andReturn($mock_groups)->getMock();
        App::instance('sentry', $mock_sentry);
        $authenticator = new SentryAuthenticator();
        $groups = $authenticator->getGroups();
        $this->assertCount(2, $groups);
    }
    
    /**
     * @test
     **/
    public function it_check_for_user_groups()
    {
        $name = "name";
        $group = new Group([
                               "name"        => $name,
                               "description" => "name"
                           ]);
        $mock_group = m::mock('StdClass')->shouldReceive('inGroup')
            ->andReturn(true)
            ->getMock();
        $mock_sentry = m::mock('StdClass')->shouldReceive('getUser')
            ->once()
            ->andReturn($mock_group)
            ->getMock();
        App::instance('sentry', $mock_sentry);
        $mock_repo = m::mock('StdClass')->shouldReceive('findByName')
            ->once()
            ->andReturn($group)
            ->getMock();
        App::instance('group_repository', $mock_repo);
        $authenticator = new SentryAuthenticator();

        $success = $authenticator->hasGroup($name);

        $this->assertTrue($success);
    }

}