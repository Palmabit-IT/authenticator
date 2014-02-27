<?php namespace Palmabit\Authentication\Validators;

use Palmabit\Library\Validators\AbstractValidator;
use Event;

class UserProfileValidator extends AbstractValidator
{
    protected static $rules = array(
        "first_name" => "max:255",
        "last_name" => "max:255",
    );

    public function __construct()
    {
        Event::listen('validating', function($input)
        {
//            static::$rules["email"][] = "unique:users,email,{$input['id']}";
//
//            if(empty($input["id"]))
//            {
//                static::$rules["password"][] = "required";
//            }
        });
    }
} 