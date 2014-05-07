<?php  namespace Palmabit\Authentication\Tests;
use Mockery as m;
use App;
/**
 * Test AuthControllerTest
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
class AuthControllerTest extends TestCase {

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     **/
    public function itLoginUserWithSuccess()
    {
        $this->loginSuccesfully();

        $this->action('POST', 'Palmabit\Authentication\Controllers\AuthController@postLogin');

        $this->assertRedirectedTo('/admin/users/list');
    }

    private function loginSuccesfully()
    {
        $mock_sentry = m::mock('StdClass')->shouldReceive('authenticate')->once()->andReturn(true)->getMock();
        App::instance('sentry', $mock_sentry);
    }

    /**
     * @test
     **/
    public function itRedirectToLandingIfNotActive()
    {
        $this->action('POST', 'Palmabit\Authentication\Controllers\AuthController@postLogin');

        $this->assertRedirectedToAction('Palmabit\Authentication\Controllers\AuthController@getLogin');
        $this->assertSessionHasErrors();
    }
}
 