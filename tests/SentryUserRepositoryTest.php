<?php  namespace Palmabit\Authentication\Tests;

use App;
use Mockery as m;
use Palmabit\Authentication\Repository\SentryUserRepository;

/**
 * Test SentryUserRepositoryTest

 */
class SentryUserRepositoryTest extends DbTestCase {


  /**
   * @test
   */
  public function can_create_user() {
    $user_repository = App::make('user_repository');
    $fake_input = [
            "email"          => $this->fake->email(),
            "password"       => $this->fake->text(10),
            "activated"      => $this->fake->boolean(50),
            "preferred_lang" => $this->fake->languageCode()
    ];
    $user_created = $user_repository->create($fake_input);
    $user_found = $user_repository->find($user_created->id);

    $this->assertEquals($fake_input['preferred_lang'], $user_found->preferred_lang);
    $this->assertEquals($user_found->email, $user_created->email);
    $this->assertEquals($user_found->password, $user_created->password);
    $this->assertEquals($user_found->activated, $user_created->activated);
  }


  /**
   * @test
   **/
  public function it_find_user_from_a_group() {
    $repo = App::make('user_repository');
    $input = [
            "email"     => "admin@admin.com",
            "password"  => "password",
            "activated" => 1
    ];
    $repo->create($input);
    $group_repo = App::make('group_repository');
    $input = [
            "name" => "admin"
    ];
    $group_repo->create($input);

    $repo->addGroup(1, 1);
    $users = $repo->findFromGroupName('admin');
    $this->assertEquals("admin@admin.com", $users[0]->email);
  }

  /**
   * @test
   **/
  public function it_gets_all_user_filtered_by_first_name_last_name_zip_email_code() {
    $per_page = 5;
    $config = m::mock('ConfigMock');
    $config->shouldReceive('get')
           ->with('authentication::users_per_page')
           ->andReturn($per_page)
           ->getMock();
    $repo = new SentryUserRepository($config);
    $input = [
            "email"     => "admin@admin.com",
            "password"  => "password",
            "activated" => 1
    ];
    $user = $repo->create($input);
    $repo_profile = App::make('profile_repository');
    $input = [
            "first_name"          => "name",
            "last_name"           => "surname",
            "billing_address_zip" => "22222",
            "code"                => "12345",
            "user_id"             => $user->id
    ];
    $repo_profile->create($input);

    $users = $repo->all(["first_name" => "name"]);
    $this->assertEquals("name", $users->first()->first_name);
    $users = $repo->all(["last_name" => "urname"]);
    $this->assertEquals("surname", $users->first()->last_name);
    $users = $repo->all(["billing_address_zip" => "22222"]);
    $this->assertEquals("22222", $users->first()->billing_address_zip);
    $users = $repo->all(["email" => "admin@admin.co"]);
    $this->assertEquals("admin@admin.com", $users->first()->email);
    $users = $repo->all(["code" => "12345", "email" => "admin@admin.com"]);
    $this->assertEquals("12345", $users->first()->code);
    $this->assertEquals(1, $users->first()->id);
  }
}
 