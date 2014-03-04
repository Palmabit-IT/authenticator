<?php namespace Palmabit\Authentication\Validators;

use Palmabit\Library\Validators\OverrideConnectionValidator;

class ReminderValidator extends OverrideConnectionValidator
{

    protected static $rules = array(
        "email" => "required",
    );
} 