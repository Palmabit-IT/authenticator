<?php namespace Palmabit\Authentication\Validators;

use Event;
use Palmabit\Library\Validators\OverrideConnectionValidator;

class GroupValidator extends OverrideConnectionValidator
{
    protected static $rules = array(
        "name" => ["required"],
    );

    public function __construct()
    {
        Event::listen('validating', function($input)
        {
            static::$rules["name"][] = "unique:groups,name,{$input['id']}";
        });
    }
} 