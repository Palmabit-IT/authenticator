<?php namespace Palmabit\Authentication\Validators;

use Palmabit\Library\Validators\OverrideConnectionValidator;
use Event;
use L;
class UserSignupValidator extends OverrideConnectionValidator
{
    public function __construct()
    {
        Event::listen('validating.withvalidator', function($validator)
        {
            $validator->addReplacer("unique", function(){return L::t('Email already exists.');});
        });
    }

    protected static $rules = array(
        "email" => ["required", "email", "unique:users,email"],
        "password" => ["required", "min:6"],
        "first_name" => "required|max:255",
        "last_name" => "required|max:255",
    );

} 