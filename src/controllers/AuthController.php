<?php namespace Palmabit\Authentication\Controllers;

use BaseController;
use View;
use Sentry;
use Input;
use Redirect;
use Palmabit\Authentication\Exceptions\AuthenticationExceptionsInterface as Aei;
use Palmabit\Authentication\SentryAuthenticator;
use Palmabit\Authentication\ReminderService;

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
        return View::make('admin.auth.login');
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
            return Redirect::to('/admin/home');
        }
        else
        {
            $errors = $this->auth->getErrors();
            return Redirect::action('Auth\Controllers\AuthController@getLogin')->withInput()->withErrors($errors);
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
        return View::make("admin.auth.reminder");
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
            return Redirect::action("Auth\\Controllers\\AuthController@getReminder")->with(array("message"=> "Nuova password inviata con successo, controlla la tua mail box."));
        }
        catch(Aei $e)
        {
            $errors = $this->reminder->getErrors();
            return Redirect::action("Auth\\Controllers\\AuthController@getReminder")->withErrors($errors);
        }
    }

    public function getChangePassword()
    {
        $email = Input::get('email');
        $token = Input::get('token');

        return View::make("admin.auth.changepassword", array("email" => $email, "token" => $token) );
    }

    public function postChangePassword()
    {
        $email = Input::get('email');
        $token = Input::get('token');
        $password = Input::get('password');

        try
        {
            $this->reminder->reset($email, $token, $password);
            return Redirect::action("Auth\\Controllers\\AuthController@getChangePassword")->with(array("message"=> "Password modificata con successo!"));
        }
        catch(Aei $e)
        {
            $errors = $this->reminder->getErrors();
            return Redirect::action("Auth\\Controllers\\AuthController@getChangePassword")->withErrors($errors);
        }

    }
}
