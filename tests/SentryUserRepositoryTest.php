<?php  namespace Palmabit\Authentication\Tests;
use App;
/**
 * Test SentryUserRepositoryTest
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
class SentryUserRepositoryTest extends DbTestCase {

    /**
     * @test
     **/
    public function it_find_user_from_a_group()
    {
        $repo = App::make('user_repository');
        $input = [
            "email" => "admin@admin.com",
            "password" => "password",
            "activated" => 1,
            "first_name" => "first_name",
            "last_name" => "last_name",
            "new_user" => 1,
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
    public function it_find_user_by_login_name()
    {
        $repo = App::make('user_repository');
        $input = [
            "email" => "admin@admin.com",
            "password" => "password",
            "activated" => 0,
            "new_user" => 1,
            "first_name" => "",
            "last_name" => ""
        ];
        $repo->create($input);

        $user = $repo->findByLogin("admin@admin.com");
        $this->assertEquals("admin@admin.com", $user->email);
    }
}
 