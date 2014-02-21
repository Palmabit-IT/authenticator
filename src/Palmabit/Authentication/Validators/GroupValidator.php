<?php namespace Palmabit\Authentication\Validators;

use Palmabit\Library\Validators\AbstractValidator;
use Event;

class GroupValidator extends AbstractValidator
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