<?php  namespace Palmabit\Authentication\Tests;

use Illuminate\Support\MessageBag;
use Palmabit\Authentication\Exceptions\UserExistsException;
use Palmabit\Authentication\Services\UserRegisterService;
use App;
use Mockery as m;
use Palmabit\Authentication\Exceptions\UserNotFoundException;
use Palmabit\Library\Exceptions\NotFoundException;
use Palmabit\Library\Exceptions\PalmabitExceptionsInterface;
use Illuminate\Database\QueryException;

/**
 * Test UserRegisterServiceTest
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
class UserRegisterServiceTest extends DbTestCase {

    public function setUp()
    {
        parent::setUp();

        $this->u_r = App::make('user_repository');

        $this->u_g = App::make('group_repository');
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     **/
    public function it_can_be_created()
    {
        new UserRegisterService();
    }

    /**
     * @test
     **/
    public function it_register_a_user_if_not_exists()
    {
        $input = [
            "email" => "test@test.com",
            "password" => "password@test.com",
            "first_name" => "first_name",
            "last_name" => "last_name",
        ];
        $mock_mailer = m::mock('StdClass')->shouldReceive('sendTo')
            ->andReturn(true)
            ->getMock();
        App::instance('palmamailer', $mock_mailer);
        $mock_auth_helper = m::mock('StdClass')->shouldReceive('getNotificationRegistrationUsersEmail')->once()->andReturn([])->getMock();
        \App::instance('authentication_helper', $mock_auth_helper);
        $mock_validator = $this->getValidatorSuccess();

        $service = new UserRegisterService($mock_validator);

        $service->register($input);

        $user = $this->u_r->find(1);
        $this->assertNotEmpty($user);
        $this->assertEquals(true,$user->new_user);
        $this->assertFalse($user->activated);
    }

    /**
     * @test
     **/
    public function it_update_user_password_if_user_already_exists_and_change_pass_if_is_imported()
    {
        $new_password = "_";
        $before_password = "__";
        $input = [
            "email" => "test@test.com",
            "password" => $before_password,
            "first_name" => "first_name",
            "last_name" => "last_name",
            "activated" => 1,
            "new_user" => 0,
            "imported" => 1
        ];
        $user_before = $this->u_r->create($input);
        $input["password"] = $new_password;
        $mock_mailer = m::mock('StdClass')->shouldReceive('sendTo')
            ->andReturn(true)
            ->getMock();
        App::instance('palmamailer', $mock_mailer);
        $mock_auth_helper = m::mock('StdClass')->shouldReceive('getNotificationRegistrationUsersEmail')->once()->andReturn([])->getMock();
        \App::instance('authentication_helper', $mock_auth_helper);
        $mock_validator = $this->getValidatorSuccess();

        $service = new UserRegisterService($mock_validator);

        $service->register($input);
        $user = $this->u_r->find(1);
        // changed the password
        $this->assertNotEquals($user->password, $user_before->password);
    }

    /**
     * @test
     **/
    public function it_dont_change_pass_and_dont_update_user_if_not_imported_and_exists()
    {
        $new_password = "_";
        $before_password = "__";
        $input = [
            "email" => "test@test.com",
            "password" => $before_password,
            "first_name" => "first_name",
            "last_name" => "last_name",
            "activated" => 1,
            "new_user" => 0,
            "imported" => null
        ];
        $user_before = $this->u_r->create($input);
        $input["password"] = $new_password;
        $mock_mailer = m::mock('StdClass')->shouldReceive('sendTo')
            ->andReturn(true)
            ->getMock();
        App::instance('palmamailer', $mock_mailer);
        $mock_auth_helper = m::mock('StdClass')->shouldReceive('getNotificationRegistrationUsersEmail')->once()->andReturn([])->getMock();
        \App::instance('authentication_helper', $mock_auth_helper);
        $mock_validator = $this->getValidatorSuccess();

        $service = new UserRegisterService($mock_validator);

        $service->register($input);
        $user = $this->u_r->find(1);
        // changed the password
        $this->assertEquals($user->password, $user_before->password);
    }

    /**
     * @test
     * @expectedException \Palmabit\Library\Exceptions\ValidationException
     **/
    public function it_throw_validation_exception_if_user_exists_is_not_imported_and_is_active_and_set_errors()
    {
        $new_password = "_";
        $before_password = "__";
        $input = [
            "email" => "test@test.com",
            "password" => $before_password,
            "first_name" => "first_name",
            "last_name" => "last_name",
            "activated" => 1,
            "new_user" => 0,
            "imported" => null,
            "form_name" => "signup"
        ];
        $user_before = $this->u_r->create($input);
        $input["password"] = $new_password;

        $service = new UserRegisterService();

        $service->register($input);
        $this->assertNotEmpty($service->getErrors());
    }

    /**
     * @test
     **/
    public function it_sends_email_to_user_if_new()
    {
        $input = [
            "email" => "test@test.com",
            "password" => "password@test.com",
            "first_name" => "first_name",
            "last_name" => "last_name",
        ];
        $mock_mailer = m::mock('StdClass')->shouldReceive('sendTo')->once()
            ->with('test@test.com', m::any(), m::any(), "authentication::mail.registration-client-new")
            ->andReturn(true)
            ->getMock();
        App::instance('palmamailer', $mock_mailer);
        $mock_auth_helper = m::mock('StdClass')->shouldReceive('getNotificationRegistrationUsersEmail')->once()->andReturn([])->getMock();
        \App::instance('authentication_helper', $mock_auth_helper);
        $this->u_g->create(["name"=> "name"]);
        $mock_validator = $this->getValidatorSuccess();

        $service = new UserRegisterService($mock_validator);

        $service->register($input);
    }

    /**
     * @test
     **/
    public function it_sends_email_to_user_if_old_but_not_active()
    {
        $input = [
            "email" => "test@test.com",
            "password" => "password@test.com",
            "first_name" => "first_name",
            "last_name" => "last_name",
            "activated" => 0,
            "new_user" => 0
        ];
        $user_before = $this->u_r->create($input);
        $mock_mailer = m::mock('StdClass')->shouldReceive('sendTo')->once()
            ->with('test@test.com', m::any(), m::any(), "authentication::mail.registration-client-exists")
            ->andReturn(true)
            ->getMock();
        App::instance('palmamailer', $mock_mailer);
        $mock_auth_helper = m::mock('StdClass')->shouldReceive('getNotificationRegistrationUsersEmail')->once()->andReturn([])->getMock();
        \App::instance('authentication_helper', $mock_auth_helper);
        $this->u_g->create(["name"=> "name"]);
        $mock_validator = $this->getValidatorSuccess();

        $service = new UserRegisterService($mock_validator);

        $service->register($input);
    }

    /**
     * @test
     **/
    public function it_sends_email_to_user_if_old_and_active()
    {
        $input = [
            "email" => "test@test.com",
            "password" => "password@test.com",
            "first_name" => "first_name",
            "last_name" => "last_name",
            "activated" => 1,
            "new_user" => 0
        ];
        $this->u_r->create($input);
        $mock_mailer = m::mock('StdClass')->shouldReceive('sendTo')->once()
            ->with('test@test.com', m::any(), m::any(), "authentication::mail.registration-activated-client")
            ->andReturn(true)
            ->shouldReceive('sendTo')
            ->getMock();
        App::instance('palmamailer', $mock_mailer);
        $mock_auth_helper = m::mock('StdClass')->shouldReceive('getNotificationRegistrationUsersEmail')->once()->andReturn([])->getMock();
        \App::instance('authentication_helper', $mock_auth_helper);
        $mock_validator = $this->getValidatorSuccess();

        $service = new UserRegisterService($mock_validator);

        $service->register($input);
    }

    /**
     * @test
     **/
    public function it_sends_email_to_admin()
    {
        $input = [
            "email" => "test@test.com",
            "password" => "password@test.com",
            "group_id" => 1,
            "comments" => '',
            "first_name" => "first_name",
            "last_name" => "last_name",
        ];
        $mock_mailer = m::mock('StdClass')->shouldReceive('sendTo')->once()
            ->andReturn(true)
            ->shouldReceive('sendTo')->once()
            ->with('admin@admin.com', m::any(), m::any(), m::any())
            ->andReturn(true)
            ->getMock();
        App::instance('palmamailer', $mock_mailer);
        $mock_auth_helper = m::mock('StdClass')->shouldReceive('getNotificationRegistrationUsersEmail')->once()->andReturn(["admin@admin.com"])->getMock();
        \App::instance('authentication_helper', $mock_auth_helper);
        $this->u_g->create(["name"=> "name"]);
        $mock_validator = $this->getValidatorSuccess();

        $service = new UserRegisterService($mock_validator);

        $service->register($input);
    }
    
    /**
     * @test
     **/
    public function it_sends_activation_email_to_the_client_on_activation()
    {
        $service = new UserRegisterService;
        $user_unactive = new \StdClass;
        $user_unactive->email = "user@user.com";
        $user_unactive->activated = 1;

        $mock_mailer = m::mock('StdClass')->shouldReceive('sendTo')->once()->with("user@user.com", m::any(), m::any(), m::any())->andReturn(true)->getMock();
        App::instance('palmamailer', $mock_mailer);

        $service->sendActivationEmailToClient($user_unactive);
    }
    
    /**
     * @test
     **/
    public function it_validates_user_input()
    {
        $mock_validator = $this->getValidatorSuccess();

        $input = [
            "email" => "test@test.com",
            "password" => "password@test.com",
            "group_id" => 1,
            "first_name" => "",
            "last_name" => "",
        ];
        $mock_mailer = m::mock('StdClass')->shouldReceive('sendTo')
            ->andReturn(true)
            ->getMock();
        App::instance('palmamailer', $mock_mailer);
        $mock_auth_helper = m::mock('StdClass')->shouldReceive('getNotificationRegistrationUsersEmail')->once()->andReturn([])->getMock();
        \App::instance('authentication_helper', $mock_auth_helper);
        $this->u_g->create(["name"=> "name"]);

        $service = new UserRegisterService($mock_validator);

        $service->register($input);
    }

    /**
     * @test
     * @expectedException \Palmabit\Library\Exceptions\ValidationException
     **/
    public function it_throw_validation_exception_if_validation_fails()
    {
        $mock_validator = $this->getValidatorFails();
        $errors = new MessageBag(["model"=> "error"]);
        $mock_validator->shouldReceive('getErrors')->andReturn($errors);

        $service = new UserRegisterService($mock_validator);

        $service->register([]);
    }

    /**
     * @test
     **/
    public function it_sets_error_if_input_validation_fails()
    {
        $mock_validator = $this->getValidatorFails();
        $errors = new MessageBag(["model"=> "error"]);
        $mock_validator->shouldReceive('getErrors')->andReturn($errors);
        $service = new UserRegisterService($mock_validator);

        try
        {
            $service->register([]);
        }
        catch(PalmabitExceptionsInterface $e)
        {}

        $errors = $service->getErrors();
        $this->assertFalse($errors->isEmpty());
    }

    /**
     * @test
     **/
    public function it_doesnt_send_email_on_activation_if_client_is_aready_active()
    {
        $service = new UserRegisterService;
        $user_unactive = new \StdClass;
        $user_unactive->email = "user@user.com";
        $user_unactive->activated = 1;

        $service->sendActivationEmailToClient($user_unactive, ["activated" => 1]);
    }

    /**
     * @test
     * @expectedException \Palmabit\Authentication\Exceptions\UserExistsException
     **/
    public function it_throws_user_exists_exception_if_user_exists()
    {
        $mock_validator = $this->getValidatorSuccess();
        $mock_repo = m::mock('StdClass')->shouldReceive('findByLogin')
            ->once()
            ->andThrow(new UserNotFoundException)
            ->shouldReceive('create')
            ->andThrow(new UserExistsException)
            ->getMock();
        App::instance('user_repository', $mock_repo);
        $service = new UserRegisterService($mock_validator);

        $service->register(["email" => ""]);
    }
    
    /**
     * @test
     **/
    public function it_throw()
    {
        
    }
    
    /**
     * @return m\MockInterface
     */
    protected function getValidatorSuccess()
    {
        $mock_validator = m::mock('Palmabit\Authentication\Validators\UserSignupValidator')->shouldReceive('validate')->once()->andReturn(true)->getMock();

        return $mock_validator;
    }

    /**
     * @return m\MockInterface
     */
    protected function getValidatorFails()
    {
        $mock_validator = m::mock('Palmabit\Authentication\Validators\UserSignupValidator')->shouldReceive('validate')->once()->andReturn(false)->getMock();

        return $mock_validator;
    }
}
