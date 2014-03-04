<?php namespace Palmabit\Authentication\Validators;

use Event;
use Palmabit\Library\Validators\OverrideConnectionValidator;

class UserProfileValidator extends OverrideConnectionValidator
{
    protected static $rules = array(
        "first_name" => "max:50",
        "code" => "max:50",
        "last_name" => "max:50",
        "phone" => "max:20",
        "vat" => "max:50",
    );
} 