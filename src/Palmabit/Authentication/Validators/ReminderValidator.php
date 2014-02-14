<?php namespace Palmabit\Authentication\Validators;

use Palmabit\Library\Validators\AbstractValidator;

class ReminderValidator extends AbstractValidator
{

    protected static $rules = array(
        "email" => "required",
    );
} 