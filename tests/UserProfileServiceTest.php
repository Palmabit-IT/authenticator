<?php  namespace Palmabit\Authentication\Tests;

use ErrorException;
use Palmabit\Authentication\Models\UserProfile;
use Palmabit\Authentication\Services\UserProfileService;
use Palmabit\Authentication\Tests\traits\CreateUserTrait;
use Palmabit\Library\Exceptions\ValidationException;
use Palmabit\Library\Validators\AbstractValidator;
use Mockery as m;
use App;

/**
 * Test UserProfileServiceTest
 */
class UserProfileServiceTest extends DbTestCase
{ use CreateUserTrait;

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     **/
    public function it_create_a_profile()
    {
        $mock_form_profile_success = m::mock('Palmabit\Library\Form\FormModel')->shouldReceive('process')->once()->andReturn(true)->getMock();

        $service = new UserProfileServiceNoPermStub(new VoidValidator(), $mock_form_profile_success);
        $user = $this->createAdmin();
        $service->processForm(['user_id'=>$user->id],$user);
    }



    /**
     * @test
     **/
    public function it_update_user_password_if_given()
    {
        $mock_form_profile_success = m::mock('Palmabit\Library\Form\FormModel')->shouldReceive('process')->andReturn(true)->getMock();
        // mock user repository
        $mock_user_repo = m::mock('StdClass')->shouldReceive('update')->once()->andReturn(true)->getMock();
        App::instance('user_repository', $mock_user_repo);
        $service = new UserProfileServiceNoPermStub(new VoidValidator(), $mock_form_profile_success);
        $user = $this->createAdmin();
        $service->processForm(["new_password" => 'pass', "user_id" => $user->id],$user);
    }

    /**
     * @test
     **/
    public function it_not_update_user_if_password_not_given()
    {
        $mock_form_profile_success = m::mock('Palmabit\Library\Form\FormModel')->shouldReceive('process')->andReturn(true)->getMock();
        // mock user repository
        App::instance('user_repository', '');
        $service = new UserProfileServiceNoPermStub(new VoidValidator(), $mock_form_profile_success);
        $user = $this->createAdmin();
        $service->processForm(["new_password" => '', "user_id" => $user->id],$user);
    }

    /**
     * @test
     **/
    public function it_return_user_profile_if_success()
    {
        $mock_form_profile_success = m::mock('Palmabit\Library\Form\FormModel')->shouldReceive('process')->andReturn(new UserProfile)->getMock();
        $service = new UserProfileServiceNoPermStub(new VoidValidator(), $mock_form_profile_success);
        $user = $this->createAdmin();
        $profile = $service->processForm(["user_id" => $user->id],$user);
        $this->assertInstanceOf('Palmabit\Authentication\Models\UserProfile', $profile);
    }

    /**
     * @test
     * @expectedException ErrorException
     **/
    public function it_not_update_profile_and_throw_exception_if_errors_perm()
    {
//        $mock_auth_helper = m::mock('StdClass')->shouldReceive('checkProfileEditPermission')->once()->andReturn(false)->getMock();
        $mock_auth_helper = m::mock('StdClass');
        App::instance('authentication_helper', $mock_auth_helper);
        $service = new UserProfileService(new VoidValidator());
        $user = $this->createAdmin();
        $service->processForm(["user_id" => $user->id],$user);
    }

    /**
     * @test
     * @expectedException ErrorException
     **/
    public function it_check_for_permission_and_set_error_incase()
    {
//        $mock_auth_helper = m::mock('StdClass')->shouldReceive('checkProfileEditPermission')->once()->andReturn(false)->getMock();
        $mock_auth_helper = m::mock('StdClass');
        App::instance('authentication_helper', $mock_auth_helper);

        $service = new UserProfileService(new VoidValidator());
        try {
            $user = $this->createAdmin();
            $service->processForm(["user_id" => $user->id],$user);
        } catch (\Palmabit\Authentication\Exceptions\PermissionException $e) {
        }

        $errors = $service->getErrors();
        $this->assertTrue($errors->has('model'));
    }
}

class VoidValidator extends AbstractValidator
{
}

class UserProfileServiceNoPermStub extends UserProfileService
{
    public function checkPermission($input = null)
    {
        //silence is golden
    }
}