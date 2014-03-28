<?php  namespace Palmabit\Authentication\Tests; 
use App;
use Palmabit\Authentication\Models\UserDbImportSaver;
use Palmabit\Authentication\Models\User;

/**
 * Test UserDbImportSaverTest
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
class UserDbImportSaverTest extends DbTestCase {

    /**
     * @test
     **/
    public function it_update_user_id_and_exists_before_saving()
    {
        $present_attributes = [
            "email" => "admin1@admin.com",
            "password" => "password",
            "activated" => 1,
            "first_name" => "first_name",
            "last_name" => "last_name",
            "new_user" => 1,
        ];
        $user_present = new UserDbImportSaver($present_attributes);
        $user_present->save();
        $expected_present_id = $user_present->id;
        $expected_present_exists = $user_present->exists;
        $expected_imported = 1;
        $user_present = new  UserDbImportSaver($present_attributes);

        $user_not_present = new UserDbImportSaver([
                             "email" => "admin2@admin.com",
                             "password" => "password",
                             "activated" => 1,
                             "first_name" => "first_name",
                             "last_name" => "last_name",
                             "new_user" => 1,
                         ]);

        $user_present->save();
        $this->assertEquals($expected_present_exists, $user_present->exists);
        $this->assertEquals($expected_present_id, $user_present->id);

        $user_not_present->save();
        $this->assertNotNull($user_not_present->id);
        $this->assertEquals($expected_imported, $user_not_present->imported);
    }
}
 