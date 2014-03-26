<?php namespace Palmabit\Authentication\Validators;

use Event;
use Palmabit\Library\Validators\OverrideConnectionValidator;

class UserProfileValidator extends OverrideConnectionValidator
{
    protected static $rules = array(
        "first_name" => "max:50|required",
        "last_name" => "max:50|required",
        "password" => "max:20|required"
    );
}