<?php namespace Palmabit\Authentication\Validators;

use Event;
use Palmabit\Library\Validators\OverrideConnectionValidator;

class UserValidator extends OverrideConnectionValidator
{
    protected static $rules = array(
        "copyEmail" => ["required", "email"],
    );

    public function __construct()
    {
        Event::listen('validating', function ($input) {
            // if the input comes from other form i just ignore that
            if (!isset($input['form_name']) || $input['form_name'] != 'user')
                return true;

            static::$rules["copyEmail"][] = "unique:users,email,{$input['id']}";

            if (empty($input["id"])) {
                static::$rules["password"][] = "required";
            }
        });
    }
} 