<?php namespace Palmabit\Authentication\Validators;

use Palmabit\Library\Validators\OverrideConnectionValidator;

class UserSignupValidator extends OverrideConnectionValidator
{
    protected static $rules = array(
        "email" => ["required", "email"],
        "password" => ["required", "min:6"],
        "first_name" => "required|max:255",
        "last_name" => "required|max:255",
    );
} 