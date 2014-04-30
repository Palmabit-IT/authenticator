<?php  namespace Palmabit\Authentication\Models; 
/**
 * Class UserDbImportSaver
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
use App;
use Palmabit\Authentication\Exceptions\UserNotFoundException;
use Palmabit\Authentication\Models\User;

class UserDbImportSaver extends User
{
    public function __construct(array $attributes = [])
    {
        // force native hasher
        $this->setHasher( new \Cartalyst\Sentry\Hashing\NativeHasher);
        return parent::__construct($attributes);
    }

    /**
     * @overrite
     * @return bool|void
     */
    public function validate()
    {
        // no validation
    }

    /**
     * @param $user
     * @return bool|void
     * @override
     */
    public function save(array $options = [])
    {
        $authenticator = App::make('authenticator');
        // if the user not already exists
        try
        {
            $user_found = $authenticator->getUser($this->getAttribute('email'));
        }
        catch(UserNotFoundException $e)
        {
            return parent::save($options);
        }
        // update existing state
        $this->setAttribute('id',$user_found->id);
        $this->exists = true;
        return parent::save($options);
    }
} 