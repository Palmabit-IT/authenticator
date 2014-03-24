<?php namespace Palmabit\Authentication\Controllers;

use BaseController;
use View;
use Sentry;
use Input;
use Redirect;
use Palmabit\Library\Exceptions\PalmabitExceptionsInterface as Pbi;
use Palmabit\Authentication\Classes\SentryAuthenticator;
use Palmabit\Authentication\Classes\ReminderService;
use L;

class AuthController extends BaseController {

    protected $auth;
    protected $reminder;

    public function __construct(SentryAuthenticator $auth, ReminderService $reminder)
    {
        $this->auth = $auth;
        $this->reminder = $reminder;
    }

    /**
     * Usato per effettuare il login utente
     *
     * @return Response
     */
    public function getLogin()
    {
        return View::make('authentication::auth.login');
    }

    public function postLogin()
    {
        $email = Input::get('email');
        $password = Input::get('password');
        $remember = Input::get('remember');

        $success = $this->auth->authenticate(array(
                                                "email" => $email,
                                                "password" => $password
                                             ), $remember);
        if($success)
        {
            return Redirect::to('/admin/users/list');
        }
        else
        {
            $errors = $this->auth->getErrors();
            return Redirect::action('Palmabit\Authentication\Controllers\AuthController@getLogin')->withInput()->withErrors($errors);
        }
    }

    /**
     * Logout utente
     * 
     * @return string
     */
    public function getLogout()
    {
        $this->auth->logout();

        return Redirect::to('/');
    }

    /**
     * Recupero password
     */
    public function getReminder()
    {
        return View::make("authentication::auth.reminder");
    }

    /**
     * Invio token per set nuova password via mail
     *
     * @return mixed
     */
    public function postReminder()
    {
        $email = Input::get('email');

        try
        {
            $this->reminder->send($email);
            return Redirect::to("/user/recupero-password-conferma");
        }
        catch(Pbi $e)
        {
            $errors = $this->reminder->getErrors();
            return Redirect::to("/user/recupero-password")->with(array('errorsReminder'=>$errors));
        }
    }

    public function getChangePassword()
    {
        $email = Input::get('email');
        $token = Input::get('token');

        return View::make("authentication::auth.changepassword", array("email" => $email, "token" => $token) );
    }

    public function postChangePassword()
    {
        $email = Input::get('email');
        $token = Input::get('token');
        $password = Input::get('password');

        try
        {
            $this->reminder->reset($email, $token, $password);
            return Redirect::home()->with(array("message"=> L::t('Password changed') ));
        }
        catch(Pbi $e)
        {
            $errors = $this->reminder->getErrors();
            return Redirect::action("Palmabit\\Authentication\\Controllers\\AuthController@getChangePassword")->withErrors($errors);
        }

    }
}
