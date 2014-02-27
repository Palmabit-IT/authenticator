<?php namespace Palmabit\Authentication\Validators;

use Palmabit\Library\Validators\AbstractValidator;
use Event;

class UserValidator extends AbstractValidator
{
    protected static $rules = array(
        "email" => ["required", "email"],
        "first_name" => "max:255",
        "last_name" => "max:255",
    );

    public function __construct()
    {
        Event::listen('validating', function($input)
        {
            // if the input comes from other form i just ignore that
            if(!isset($input['form_name']) || $input['form_name']!='user')
                return true;

            static::$rules["email"][] = "unique:users,email,{$input['id']}";

            if(empty($input["id"]))
            {
                static::$rules["password"][] = "required";
            }
        });
    }
} 