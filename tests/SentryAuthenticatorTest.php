<?php
use Mockery as m;
use Palmabit\Authentication\Classes\SentryAuthenticator;

class SentryAuthenticatorTest extends Orchestra\Testbench\TestCase {

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
        $mock_auth = m::mock('Auth\SentryAuthenticator')->makePartial();
        $mock_auth->shouldReceive('getUser')->andReturn($mock_user);

        $token = $mock_auth->getToken("");
        $this->assertEquals(true, $token);
    }

}