<?php namespace Palmabit\Authentication\Tests;

use Mockery as m;
use App, Event;
use Palmabit\Authentication\Classes\SentryAuthenticator;
use Palmabit\Authentication\Models\Group;
use Palmabit\Authentication\Repository\SentryUserRepository;

class SentryAuthenticatorTest extends DbTestCase
{

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
        $mock_groups = m::mock('StdClass')->shouldReceive('getGroups')->andReturn([new Group, new Group])->getMock();
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
            "name" => $name,
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

    /**
     * @test
     **/
    public function it_gets_logged_user_profile()
    {
        $expected_profile = "profile";
        $get_mock = m::mock('StdClass')
            ->shouldReceive('first')
            ->once()
            ->andReturn($expected_profile)
            ->getMock();
        $mock_user_profile = m::mock('StdClass')
            ->shouldReceive('user_profile')
            ->once()
            ->andReturn($get_mock)
            ->getMock();
        $mock_sentry = m::mock('StdClass')
            ->shouldReceive('getUser')
            ->once()
            ->andReturn($mock_user_profile)
            ->getMock();
        App::instance('sentry', $mock_sentry);

        $authenticator = new SentryAuthenticator();
        $user_profile = $authenticator->getLoggedUserProfile();
        $this->assertEquals($expected_profile, $user_profile);
    }


    /**
     * @test
     * @group a
     **/
    public function it_find_user_by_id()
    {
        $repo = new SentryUserRepository();
        $user_saved = $repo->create([
            "email" => "mail@mail.com",
            "password" => "password",
            "activated" => 1
        ]);

        $user_found = $repo->find(1);

        $this->assertEquals($user_found->email, $user_saved->email);
    }

    /**
     * @test
     **/
    public function itFireEventOnAuthenticate()
    {
        $mock_sentry = $this->mockSentryAuthenticateSuccess();
        App::instance('sentry', $mock_sentry);

        $authenticator = new SentryAuthenticator;

        $found = false;
        Event::listen('authentication.login', function () use (&$found) {
            $found = true;
        });

        $authenticator->authenticate([]);
        $this->assertTrue($found);
    }

    /**
     * @return m\MockInterface
     */
    private function mockSentryAuthenticateSuccess()
    {
        $mock_sentry = m::mock('StdClass')
            ->shouldReceive('authenticate')
            ->andReturn(new \StdClass)
            ->getMock();

        return $mock_sentry;
    }
}