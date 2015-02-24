<?php namespace Palmabit\Authentication\Models;

/**
 * Class User
 */

use Cartalyst\Sentry\Users\Eloquent\User as CartaUser;
use Cartalyst\Sentry\Users\UserExistsException;
use Palmabit\Library\Traits\OverrideConnectionTrait;

class User extends CartaUser
{
    use OverrideConnectionTrait;

    protected $fillable = [
        "email",
        "copyEmail",
        "copyPassword",
        "password",
        "permissions",
        "activated",
        "activation_code",
        "activated_at",
        "last_login",
        "preferred_lang",
        "blocked"
    ];

    protected $guarded = ["id"];


    /**
     * Validates the user and throws a number of
     * Exceptions if validation fails.
     *
     * @override
     * @return bool
     * @throws LoginRequiredException
     * @throws \Cartalyst\Sentry\Users\UserExistsException
     */
    public function validate()
    {
        if (!$login = $this->{static::$loginAttribute}) {
            throw new LoginRequiredException("A login is required for a user, none given.");
        }

        // Check if the user already exists
        $query = $this->newQuery();
        $persistedUser = $query->where($this->getLoginName(), '=', $login)->first();

        if ($persistedUser and $persistedUser->getId() != $this->getId()) {
            throw new UserExistsException("A user already exists with login [$login], logins must be unique for users.");
        }

        return true;
    }

    public function user_profile()
    {
        return $this->hasMany('Palmabit\Authentication\Models\UserProfile');
    }

    /**
     * Override for form field
     *
     * @return mixed
     */
    public function getCopyEmailAttribute()
    {
        return $this->getAttribute('email');
    }

    /**
     * Override for form field
     *
     * @return mixed
     */
    public function getCopyPasswordAttribute()
    {
        return $this->getAttribute('password');
    }

    /**
     * Override for form field
     *
     * @param $value
     */
    public function setCopyEmailAttribute($value)
    {
        return $this->setAttribute('email', $value);
    }

    /**
     * Override for form field
     *
     * @param $value
     */
    public function setCopyPasswordAttribute($value)
    {
        return $this->setAttribute('password', $value);
    }
} 