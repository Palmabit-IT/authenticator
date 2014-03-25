<?php namespace Palmabit\Authentication\Validators;

use Palmabit\Library\Validators\OverrideConnectionValidator;
use Event, App;
use Illuminate\Support\MessageBag;
use Palmabit\Library\Exceptions\ValidationException;

class UserSignupValidator extends OverrideConnectionValidator
{
    protected static $rules = array(
        "email" => ["required", "email"],
        "password" => ["required", "min:6"],
        "first_name" => "required|max:255",
        "last_name" => "required|max:255",
    );

    public function __construct()
    {
        Event::listen('validating', function($input)
        {
            // if the input comes from other form i just ignore that
            if(!isset($input['form_name']) || $input['form_name']!='signup')
                return true;

            try
            {
                // try to update the user
                $user = App::make('user_repository')->findByLogin($input["email"]);
                if($user->activated && ! $user->new_user && ! $user->imported)
                {
                    $this->errors = new MessageBag(["email" => "Esiste già un'utente con questa email."]);
                    throw new ValidationException;
                }
            }
            catch(UserNotFoundException $e)
            {}
        });
    }
} 