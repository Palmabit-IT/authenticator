<?php namespace Palmabit\Authentication\Validators;

use Palmabit\Library\Validators\OverrideConnectionValidator;

class UserSignupValidator extends OverrideConnectionValidator
{
    public function __construct()
    {
        Event::listen('validating.withvalidator', function($validator)
        {
            $validator->addReplacer("unique", "Email");
        });
    }

    protected static $rules = array(
        "email" => ["required", "email", "unique:users,email"],
        "password" => ["required", "min:6"],
        "first_name" => "required|max:255",
        "last_name" => "required|max:255",
        "phone" => "required",
        "vat" => "numeric",
        "agree" => "accepted",
        "billing_state" => "required",
        "billing_country" => "required"
    );

} 