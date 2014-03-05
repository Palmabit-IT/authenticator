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
}
 