<?php namespace Palmabit\Authentication\Validators;

use Event;
use Palmabit\Library\Validators\OverrideConnectionValidator;

class UserImportValidator extends OverrideConnectionValidator
{
    protected static $rules = array(
        "file" => ["required"],
    );
} 