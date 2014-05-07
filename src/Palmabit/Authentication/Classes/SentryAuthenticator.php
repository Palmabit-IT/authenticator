<?php namespace Palmabit\Authentication\Classes;
/**
 * Class SentryAuthenticator
 *
 * Autenticatore che utilizza sentry
 *
 * @package Auth
 * @author jacopo beschi j.beschi@palmabit.com
 */
use Illuminate\Support\Facades\App;
use Illuminate\Support\MessageBag;
use Palmabit\Authentication\Exceptions\UserNotFoundException;
use Palmabit\Authentication\Interfaces\AuthenticateInterface;
use Palmabit\Authentication\Models\User;
use Event;

class SentryAuthenticator implements AuthenticateInterface{

    protected $errors;

    protected $sentry;

    public function __construct()
    {
        $this->sentry = \App::make('sentry');
        $this->errors = new MessageBag();
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate($credentials, $remember = false)
    {
        try
        {
            $user = $this->sentry->authenticate($credentials, $remember);
        }
        catch (\Cartalyst\Sentry\Users\LoginRequiredException $e)
        {
            $this->errors->add('login','Il campo login è richiesto.');
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $this->errors->add('login','Login fallito.');
        }
        catch (\Cartalyst\Sentry\Users\UserNotActivatedException $e)
        {
            $this->errors->add('login','Utente non è stato attivato.');
        }
        catch(\Cartalyst\Sentry\Users\PasswordRequiredException $e)
        {
            $this->errors->add('login','Il campo password è richiesto.');
        }

        if($this->errors->isEmpty())
        {
            Event::fire('authentication.login',[$user]);
            return $user;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * {@inheritdoc}
     */
    public function loginById($id, $remember = false)
    {
        $user = $this->sentry->findUserById($id);

        try
        {
            $this->sentry->login($user, false);
        }
        catch (\Cartalyst\Sentry\Users\LoginRequiredException $e)
        {
            $this->errors->add('login','Login richiesto.');
        }
        catch (\Cartalyst\Sentry\Users\UserNotActivatedException $e)
        {
            $this->errors->add('login','Utente non attivo.');
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $this->errors->add('login','Utente non trovato.');
        }

        return $this->errors->isEmpty() ? true : false;
    }

    /**
     * {@inheritdoc}
     */
    public function logout()
    {
        $this->sentry->logout();
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($email)
    {
        try
        {
            $user = $this->sentry->findUserByLogin($email);
        }
        catch(\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            throw new UserNotFoundException($e->getMessage());
        }
        return $user;
    }

    /**
     * {@inheritdoc}
     * @throws \Palmabit\Authentication\Exceptions\UserNotFoundException
     */
    public function getToken($email)
    {
        $user = $this->getUser($email);

        return $user->getResetPasswordCode();
    }

    /**
     * {@inheritdoc}
     */
    public function check()
    {
        return $this->sentry->check();
    }

    /**
     * Obtain the current user groups
     *
     * @param $name
     * @return array
     */
    public function getGroups()
    {
        $user = $this->getLoggedUser();
        return $user ? $user->getGroups() : [];
    }

    /**
     * Check if the current user has the given group
     *
     * @param $name
     * @return mixed
     */
    public function hasGroup($name)
    {
        $group = App::make('group_repository')->findByName($name);
        $user = $this->getLoggedUser();

        if(! $user ) return false;
        return $user->inGroup($group);
    }

    public function getLoggedUser()
    {
        return $this->sentry->getUser();
    }

    public function getLoggedUserProfile()
    {
        $user = $this->getLoggedUser();
        return $user->user_profile()->first();
    }

    public function findById($id)
    {
        return User::find($id);
    }
}
