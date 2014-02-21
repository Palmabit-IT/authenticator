<?php namespace Palmabit\Authentication\Validators;

use Palmabit\Library\Validators\AbstractValidator;
use Event;

class PermissionValidator extends AbstractValidator
{
    protected static $rules = array(
        "description" => ["required", "max:255"],
        "permission" => ["required", "max:255"],
    );

    public function __construct()
    {
        Event::listen('validating', function($input)
        {
            static::$rules["permission"][] = "unique:permission,permission,{$input['id']}";
        });
    }
} 