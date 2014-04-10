<?php  namespace Palmabit\Authentication\Tests; 
use App;
use Palmabit\Authentication\Models\User;
/**
 * Test UserTest
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
class UserTest extends DbTestCase {

    /**
     * @test
     **/
    public function it_gets_email_when_try_to_get__email()
    {
        $repo = App::make('user_repository');
        $input = [
            "email" => "admin@admin.com",
            "password" => "password",
            "activated" => 1
        ];
        $user = $repo->create($input);
        $this->assertEquals($user->copyEmail, $user->email);
    }

    /**
     * @test
     **/
    public function it_sets_email_when_try_to_get__email()
    {
        $repo = App::make('user_repository');
        $input = [
            "email" => "admin@admin.com",
            "password" => "password",
            "activated" => 1
        ];
        $user = $repo->create($input);
        $new_email = "new@new.com";
        $user = $repo->update(1,["copyEmail" => $new_email]);
        $this->assertEquals($new_email, $user->email);
    }
}
 