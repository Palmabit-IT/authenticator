<?php  namespace Palmabit\Authentication\Tests;

use Illuminate\Support\MessageBag;
use Palmabit\Authentication\Exceptions\UserExistsException;
use Palmabit\Authentication\Services\UserRegisterService;
use App;
use Mockery as m;
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
    public function it_register_a_user()
    {
        $input = [
            "email" => "test@test.com",
            "password" => "password@test.com",
            "group_id" => 1
        ];
        $mock_mailer = m::mock('StdClass')->shouldReceive('sendTo')
            ->andReturn(true)
            ->getMock();
        App::instance('palmamailer', $mock_mailer);
        $mock_auth_helper = m::mock('StdClass')->shouldReceive('getNotificationRegistrationUsersEmail')->once()->andReturn([])->getMock();
        \App::instance('authentication_helper', $mock_auth_helper);
        $mock_validator = $this->getValidatorSuccess();

        $service = new UserRegisterService($mock_validator);
        $this->u_g->create(["name"=> "name"]);

        $service->register($input);

        $user = $this->u_r->find(1);
        $this->assertNotEmpty($user);
    }

    /**
     * @test
     **/
    public function it_create_a_profile()
    {
        $input = [
            "email" => "test@test.com",
            "password" => "password@test.com",
            "group_id" => 1
        ];
        $mock_mailer = m::mock('StdClass')->shouldReceive('sendTo')
            ->andReturn(true)
            ->getMock();
        App::instance('palmamailer', $mock_mailer);
        $mock_auth_helper = m::mock('StdClass')->shouldReceive('getNotificationRegistrationUsersEmail')->once()->andReturn([])->getMock();
        \App::instance('authentication_helper', $mock_auth_helper);
        $this->u_g->create(["name"=> "name"]);
        $mock_validator = $this->getValidatorSuccess();

        $service = new UserRegisterService($mock_validator);

        $service->register($input);

        $user = $this->u_r->find(1);
        $profile = App::make('profile_repository')->getFromUserId($user->id);
        $this->assertNotEmpty($profile);
    }
    
    /**
     * @deprecated test
     **/
    public function it_associate_a_given_group()
    {
        $input = [
            "email" => "test@test.com",
            "password" => "password@test.com",
            "group_id" => 1
        ];
        $mock_mailer = m::mock('StdClass')->shouldReceive('sendTo')
            ->andReturn(true)
            ->getMock();
        App::instance('palmamailer', $mock_mailer);
        $mock_auth_helper = m::mock('StdClass')->shouldReceive('getNotificationRegistrationUsersEmail')->once()->andReturn([])->getMock();
        $mock_user = new \StdClass;
        $mock_user->id = 1;
        $mock_user->email = "";
        \App::instance('authentication_helper', $mock_auth_helper);
        $mock_user_repo = m::mock('\Palmabit\Authentication\Repository\SentryUserRepository')
            ->shouldReceive('create')->once()->andReturn($mock_user)
            ->shouldReceive('addGroup')->once()
            ->getMock();
        \App::instance('user_repository', $mock_user_repo);
        $mock_user_profile = m::mock('StdClass')->shouldReceive('create')->once()->andReturn(true)->getMock();
        \App::instance('profile_repository', $mock_user_profile);
        $this->u_g->create(["name"=> "name"]);
        $mock_validator = $this->getValidatorSuccess();

        $service = new UserRegisterService($mock_validator);

        $service->register($input);
    }

    /**
     * @test
     **/
    public function it_sends_email_to_user()
    {
        $input = [
            "email" => "test@test.com",
            "password" => "password@test.com",
            "group_id" => 1
        ];
        $mock_mailer = m::mock('StdClass')->shouldReceive('sendTo')->once()
            ->with('test@test.com', m::any(), m::any(), m::any())
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
    public function it_sends_email_to_admin()
    {
        $input = [
            "email" => "test@test.com",
            "password" => "password@test.com",
            "group_id" => 1,
            "comments" => ''
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
        $user_unactive->activated = 0;

        $mock_mailer = m::mock('StdClass')->shouldReceive('sendTo')->once()->with("user@user.com", m::any(), m::any(), m::any())->andReturn(true)->getMock();
        App::instance('palmamailer', $mock_mailer);

        $service->sendActivationEmailToClient($user_unactive, ["activated" => 1, "email" => '']);
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
            "group_id" => 1
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
        $mock_repo = m::mock('StdClass')->shouldReceive('create')->andThrow(new UserExistsException)->getMock();
        App::instance('user_repository', $mock_repo);
        $service = new UserRegisterService($mock_validator);

        $service->register([]);
    }

    /**
     * @deprecated test
     * @expectedException \Palmabit\Library\Exceptions\NotFoundException
     **/
    public function it_throws_not_found_exception_if_cannot_find_the_user()
    {
        $mock_validator = $this->getValidatorSuccess();
        $user_stub = new \StdClass;
        $user_stub->id = 1;
        $mock_repo = m::mock('StdClass')->shouldReceive('create')->andReturn($user_stub)
            ->shouldReceive('addGroup')->andThrow(new NotFoundException)
            ->getMock();
        App::instance('user_repository', $mock_repo);
        $service = new UserRegisterService($mock_validator);

        $service->register(["group_id" => 1]);
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
