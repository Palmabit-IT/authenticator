<?php namespace Palmabit\Authentication\Validators;

use Palmabit\Library\Validators\AbstractValidator;

class UserSignupValidator extends AbstractValidator
{
    protected static $rules = array(
      "email" => ["required", "email", "unique:users,email"],
      "password" => ["required", "min:6"],
      "first_name" => "required|max:255",
      "last_name" => "required|max:255",
      "agree" => "accepted"
    );
} 