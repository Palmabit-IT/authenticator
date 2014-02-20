<?php namespace Palmabit\Authentication\Models;
/**
 * Class User
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
use Cartalyst\Sentry\Users\Eloquent\User as CartaUser;

class User extends CartaUser
{
    protected $fillable = ["email", "password", "first_name", "last_name", "permissions", "activated", "activation_code", "activated_at", "last_login"];

    protected $guarded = ["id"];

    /**
     * @override
     * @return \Illuminate\Database\Connection
     */
    public function getConnection()
    {
        return static::resolveConnection('authentication');
    }

    /**
     * Validates the user and throws a number of
     * Exceptions if validation fails.
     *
     * @override
     * @return bool
     * @throws \Cartalyst\Sentry\Users\UserExistsException
     */
    public function validate()
    {
        if ( ! $login = $this->{static::$loginAttribute})
        {
            throw new LoginRequiredException("A login is required for a user, none given.");
        }

        // Check if the user already exists
        $query = $this->newQuery();
        $persistedUser = $query->where($this->getLoginName(), '=', $login)->first();

        if ($persistedUser and $persistedUser->getId() != $this->getId())
        {
            throw new UserExistsException("A user already exists with login [$login], logins must be unique for users.");
        }

        return true;
    }

} 