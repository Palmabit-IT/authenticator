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
    public function it_import_user_data()
    {
        $user_attributes = [
            "email" => "admin1@admin.com",
            "password" => "password",
            "activated" => 1,
         ];
        $user = new UserDbImportSaver($user_attributes);
        $user->save();
        $user_id = $user->id;
        $user_exists = $user->exists;
        $user = new  UserDbImportSaver($user_attributes);

        $user->save();
        $this->assertEquals($user_exists, $user->exists);
        $this->assertEquals($user_id, $user->id);
    }
}
 