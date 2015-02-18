<?php namespace Palmabit\Authentication\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use App;

class DbSeeder extends Seeder
{
    public function run()
    {
        Eloquent::unguard();

        $this->call('Palmabit\Authentication\Seeds\PermissionSeeder');
        $this->call('Palmabit\Authentication\Seeds\GroupsSeeder');
        $this->call('Palmabit\Authentication\Seeds\UserSeeder');

        Eloquent::reguard();
    }
}

class PermissionSeeder
{
    public function run()
    {
        $permission_repository = App::make('permission_repository');
        $permission1 = [
            "description" => "superadmin",
            "permission" => "_superadmin",
            "blocked" => 1
        ];
        $permission_repository->create($permission1);
        $permission2 = [
            "description" => "admin",
            "permission" => "_admin",
            "blocked" => 1
        ];
        $permission_repository->create($permission2);
        $permission3 = [
            "description" => "profile editor",
            "permission" => "_profile_editor"
        ];
    }
}

/**
 * @property mixed group_repository
 */
class GroupsSeeder
{

    public function run()
    {
        $group_repository = App::make('group_repository');

        $group1 = [
            "name" => "superadmin",
            "permissions" => ["_superadmin" => 1, "_admin" => 1]
        ];

        $group_repository->create($group1);

        $group2 = [
            "name" => "admin",
            "permissions" => ["_admin" => 1]
        ];

        $group_repository->create($group2);

        $group3 = [
            "name" => "mail notification",
            "permissions" => []
        ];

        $group_repository->create($group3);
    }
}

class UserSeeder
{
    protected $admin_email = "admin@admin.com";
    protected $admin_password = "password";

    public function run()
    {
        $user_repository = App::make('user_repository');
        $group_repository = App::make('group_repository');
        $profile_repository = App::make('profile_repository');

        $user_data = [
            "email" => $this->admin_email,
            "password" => $this->admin_password,
            "activated" => 1
        ];

        $user = $user_repository->create($user_data);

        $profile_repository->attachEmptyProfile($user);

        $superadmin_group = $this->getSuperadminGroup($group_repository);
        $user_repository->addGroup($user->id, $superadmin_group->id);
        $mail_notification_group = $this->getMailNotificationGroup($group_repository);
        $user_repository->addGroup($user->id, $mail_notification_group->id);
    }

    /**
     * @param $group_repository
     * @return mixed
     */
    private function getSuperadminGroup($group_repository)
    {
        $superadmin_group = $group_repository->all(["name" => "superadmin"])->first();
        return $superadmin_group;
    }


    /**
     * @param $group_repository
     * @return mixed
     */
    private function getMailNotificationGroup($group_repository)
    {
        $superadmin_group = $group_repository->all(["name" => "mail notification"])->first();
        return $superadmin_group;
    }
}