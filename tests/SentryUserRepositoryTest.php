<?php  namespace Palmabit\Authentication\Tests;

use App;
use Illuminate\Support\Facades\Config;
use Mockery as m;
use Palmabit\Authentication\Repository\SentryUserRepository;
use Palmabit\Authentication\Tests\traits\CreateGroupTrait;
use Palmabit\Authentication\Tests\traits\CreateUserTrait;

/**
 * Test SentryUserRepositoryTest

 */
class SentryUserRepositoryTest extends DbTestCase
{
    use CreateUserTrait, CreateGroupTrait;

    /**
     * @test
     */
    public function can_create_user()
    {
        $user_repository = App::make('user_repository');
        $fake_input = [
            "email" => $this->fake->email(),
            "password" => $this->fake->text(10),
            "activated" => $this->fake->boolean(50),
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
    public function it_find_user_from_a_group()
    {
        $repo = App::make('user_repository');
        $this->createSuperadmin();
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
    public function it_gets_all_user_filtered_by_first_name_last_name_zip_email_code()
    {
        $repo = $this->repository();
        $user = $this->createSuperadmin();
        $repo_profile = App::make('profile_repository');
        $input = [
            "first_name" => "name",
            "last_name" => "surname",
            "billing_address_zip" => "22222",
            "code" => "12345",
            "user_id" => $user->id,
        ];
        $repo_profile->create($input);
        $users = $repo->all(["first_name" => "name"]);
        $this->assertEquals("name", $users->first()->first_name);
        $users = $repo->all(["last_name" => "surname"]);
        $this->assertEquals("surname", $users->first()->last_name);
        $users = $repo->all(["billing_address_zip" => "22222"]);
        $this->assertEquals("22222", $users->first()->billing_address_zip);
        $users = $repo->all(["email" => "admin@admin.com"]);
        $this->assertEquals("admin@admin.com", $users->first()->email);
        $users = $repo->all(["code" => "12345", "email" => "admin@admin.com"]);
        $this->assertEquals("12345", $users->first()->code);
        $this->assertEquals(1, $users->first()->id);
    }

    /**
     * @test
     */
    public function exclude_user_group()
    {
        $repo = $this->repository();
        $this->createUserStub();
        $listUser = $repo->all();
        $usersExclude = $repo->excludeUserGroup($listUser, true, ['admin']);
        foreach ($usersExclude->get() as $user) {
            $this->assertEquals('user@user.com', $user->email);
        }
    }

    private function createUserStub()
    {
        $repo = $this->repository();
        $admin = $this->createSuperadmin();
        $user = $this->createAdmin();
        $adminGroup = $this->createAdminGroup();
        $userGroup = $this->createUserGroup();
        $repo->addGroup($admin->id, $adminGroup->id);
        $repo->addGroup($user->id, $userGroup->id);
    }


    /**
     * @test
     */
    public function isLoggedUserIsExcludedGroupTest()
    {
        $admin = $this->createSuperadmin();
        $user = $this->createAdmin();
        $adminGroup = $this->createAdminGroup();
        $userGroup = $this->createUserGroup();
        $repo = $this->repository();
        $repo->addGroup($admin->id, $adminGroup->id);
        $repo->addGroup($user->id, $userGroup->id);
        $this->assertFalse($repo->inGroupExlude($user, [
                'exclude' => true,
                'exclude_type' => ['User']
            ]
        ));
    }

    /**
     * @test
     * @expectedException Cartalyst\Sentry\Groups\GroupNotFoundException
     */
    public function permissionToAddGroupTest()
    {
        $user = $this->createAdmin();
        $adminGroup = $this->createAdminGroup();
        $superadminGroup = $this->createSuperadminGroup();
        $sentryUserRepository = new SentryUserRepository();
        $repo = $this->repository();
        $repo->addGroup($user->id, $adminGroup->id);
        $sentryUserRepository->permissionToAddGroup($user->id, $superadminGroup->id);
    }

    /**
     * @test
     * @expectedException Palmabit\Authentication\Exceptions\ProfileNotFoundException
     */
    public function permissionToEditOtherUserTest()
    {
        $user = $this->createAdmin();
        $adminGroup = $this->createAdminGroup();
        $superadmin = $this->createSuperadmin();
        $superadminGroup = $this->createSuperadminGroup();
        $sentryUserRepository = new SentryUserRepository();
        $repo = $this->repository();
        $repo->addGroup($user->id, $adminGroup->id);
        $repo->addGroup($superadmin->id, $superadminGroup->id);
        $sentryUserRepository->hasPermissionToEditUser($user, $superadmin->id);


    }

}
 