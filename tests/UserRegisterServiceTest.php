<?php  namespace Palmabit\Authentication\Tests;

use Palmabit\Authentication\Services\UserRegisterService;
use App;
use Mockery as m;
/**
 * Test UserRegisterServiceTest
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
class UserRegisterServiceTest extends DbTestCase {

    /* STEP:
     * register a user
     * send email to both user and all _admin users with user info on user and less on admin
     * on activation send again email
     * add profile input (VAT non obbligatoria)
     * save group on creation: Professional e amateur
     * -> go from here
     * to the admin send the comments
     * use transaction on db save
     */

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
        $service = new UserRegisterService();
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

        $service = new UserRegisterService();

        $service->register($input);

        $user = $this->u_r->find(1);
        $profile = App::make('profile_repository')->getFromUserId($user->id);
        $this->assertNotEmpty($profile);
    }
    
    /**
     * @test
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

        $service = new UserRegisterService();

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

        $service = new UserRegisterService();

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
            "group_id" => 1
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

        $service = new UserRegisterService();

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

        $service->sendActivationEmailToClient($user_unactive, ["activated" => 1]);
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
}
