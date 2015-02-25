<?php  namespace Palmabit\Authentication\Controllers;

use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Illuminate\Support\MessageBag;
use Palmabit\Authentication\Exceptions\PermissionException;
use Palmabit\Authentication\Exceptions\ProfileNotFoundException;
use Palmabit\Authentication\Exceptions\UserExistsException;
use Palmabit\Authentication\Helpers\SentryAuthenticationHelper;
use Palmabit\Authentication\Models\UserProfile;
use Palmabit\Authentication\Repository\SentryUserRepository as Repo;
use Palmabit\Authentication\Services\UserRegisterService;
use Palmabit\Authentication\Validators\UserSignupValidator;
use Palmabit\Library\Form\FormModel;
use Palmabit\Authentication\Models\User;
use Palmabit\Authentication\Exceptions\UserNotFoundException;
use Palmabit\Authentication\Validators\UserValidator;
use Palmabit\Authentication\Validators\UserProfileValidator;
use Palmabit\Library\Exceptions\PalmabitExceptionsInterface;
use View, Input, Redirect, App, Config;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Palmabit\Authentication\Services\UserProfileService;
use L, URL;

/**
 * Class UserController
 */
class UserController extends \BaseController
{
    /**
     * @var \Palmabit\Authentication\Repository\SentryUserRepository
     */
    protected $r;
    /**
     * @var \Palmabit\Authentication\Validators\UserValidator
     */
    protected $v;
    /**
     * Profile repository
     */
    protected $r_p;
    /**
     * @var \Palmabit\Authentication\Validators\UserProfileValidator
     */
    protected $v_p;
    /**
     * @var \Palmabit\Authentication\Validators\UserSignupValidator
     */
    protected $v_s;
    /**
     * Sentry instance
     */
    protected $sentry;

    protected $sentryAuthenticationHelper;

    public function __construct(Repo $r, UserValidator $v, UserProfileValidator $vp, UserSignupValidator $vs)
    {
        $this->r = $r;
        $this->v = $v;
        $this->f = new FormModel($this->v, $this->r);
        $this->v_p = $vp;
        $this->r_p = App::make('profile_repository');
        $this->v_s = $vs;
        $this->sentry = \App::make('sentry');
        $this->sentryAuthenticationHelper = new SentryAuthenticationHelper();
    }

    public function getList()
    {
        $exclude = Config::get('authentication::exclude_user_type');
        $allUsers = $this->r->all(Input::all());
        $execute = $this->r->inGroupExlude($this->sentry->getUser(), $exclude);
        $usersExclude = $this->r->excludeUserGroup($allUsers, $execute, $exclude['exclude_type']);
        $users = $this->r->paginate($usersExclude);
        $users = $this->r->checkEditablePermission($users, $this->sentry->getUser());
        return View::make('authentication::user.list')->with(["users" => $users]);
    }

    public function editUser()
    {
        $id = Input::get('id');
        if ($this->sentryAuthenticationHelper->checkAccessPage($id)) {
            try {
                $user = $this->r->find($id);
            } catch (PalmabitExceptionsInterface $e) {
                $user = new User;
            }

            return View::make('authentication::user.edit')->with(["user" => $user]);
        } else {
            return Redirect::action('Palmabit\Authentication\Controllers\UserController@getList')
                ->withErrors(new MessageBag(['Non puoi accedere all\'area richiesta']));
        }
    }

    public function postEditUser()
    {
        $id = Input::get('id');
        try {
            $this->r->hasPermissionToEditUser($this->sentry->getUser(), $id);
        } catch (PermissionException $e) {
            return Redirect::route("users.edit", $id ? ["id" => $id] : [])->withInput()
                ->withErrors(new MessageBag(["permissionNotAllowed" => "Non hai i permessi per apportare modifiche a questo utente"]));
        }
        try {
            $obj = $this->f->process(Input::all());
        } catch (PalmabitExceptionsInterface $e) {
            $errors = $this->f->getErrors();
            // passing the id incase fails editing an already existing item
            return Redirect::route("users.edit", $id ? ["id" => $id] : [])->withInput()->withErrors($errors);
        }
        return Redirect::action('Palmabit\Authentication\Controllers\UserController@editUser', ["id" => $obj->id])
            ->withMessage("Utente modificato con successo.");
    }

    public function deleteUser()
    {
        try {
            $this->r->hasPermissionToEditUser($this->sentry->getUser(), Input::get('id'));
        } catch (PermissionException $e) {
            return Redirect::back()
                ->withErrors(new MessageBag(["permissionNotAllowed" => "Non hai i permessi apportare modifiche a questo utente"]));
        }
        try {
            $this->f->delete(Input::all());
        } catch (PalmabitExceptionsInterface $e) {
            $errors = $this->f->getErrors();
            return Redirect::action('Palmabit\Authentication\Controllers\UserController@getList')->withErrors($errors);
        }
        return Redirect::action('Palmabit\Authentication\Controllers\UserController@getList')->withMessage("Utente cancellato con successo.");
    }

    public function addGroup()
    {
        $user_id = Input::get('id');
        $group_id = Input::get('group_id');
        try {
            $this->r->hasPermissionToEditUser($this->sentry->getUser(), Input::get('id'));
            $this->r->permissionToAddGroup($this->sentry->getUser()->id, $group_id);
            $this->r->addGroup($user_id, $group_id);
        } catch (PermissionException $e) {
            return Redirect::back()
                ->withErrors(new MessageBag(["permissionNotAllowed" => "Non hai i permessi per apportare modifiche a questo utente"]));
        } catch (GroupNotFoundException $e) {
            return Redirect::action('Palmabit\Authentication\Controllers\UserController@editUser', ["id" => $user_id])
                ->withErrors(new MessageBag(["name" => "Non hai i permessi per aggiungere il gruppo selezionato"]));
        } catch (ModelNotFoundException $e) {
            return Redirect::action('Palmabit\Authentication\Controllers\UserController@editUser', ["id" => $user_id])
                ->withErrors(new MessageBag(["name" => "Qualcosa è andato storto nell'associazione del gruppo"]));
        } catch (PalmabitExceptionsInterface $e) {
            return Redirect::action('Palmabit\Authentication\Controllers\UserController@editUser', ["id" => $user_id])
                ->withErrors(new MessageBag(["name" => "Gruppo non presente."]));
        }
        return Redirect::action('Palmabit\Authentication\Controllers\UserController@editUser', ["id" => $user_id])
            ->withMessage("Gruppo aggiunto con successo.");
    }

    public function deleteGroup()
    {
        $user_id = Input::get('id');
        $group_id = Input::get('group_id');
        try {
            $this->r->hasPermissionToEditUser($this->sentry->getUser(), Input::get('id'));
        } catch (PermissionException $e) {
            return Redirect::back()->withErrors(new MessageBag(["permissionNotAllowed" => "Non hai i permessi per apportare modifiche a questo utente"]));
        }
        try {
            $this->r->removeGroup($user_id, $group_id);
        } catch (PalmabitExceptionsInterface $e) {
            return Redirect::action('Palmabit\Authentication\Controllers\UserController@editUser', ["id" => $user_id])
                ->withErrors(new MessageBag(["name" => "Gruppo non presente."]));
        }
        return Redirect::action('Palmabit\Authentication\Controllers\UserController@editUser', ["id" => $user_id])
            ->withMessage("Gruppo cancellato con successo.");
    }

    public function editProfile()
    {
        $user_id = Input::get('user_id');
        if ($this->sentryAuthenticationHelper->checkAccessPage($user_id)) {
            try {
                $user_profile = $this->r_p->getFromUserId($user_id);
            } catch (UserNotFoundException $e) {
                return Redirect::action('Palmabit\Authentication\Controllers\UserController@getList')
                    ->withErrors(new MessageBag(['model' => 'Utente non presente.']));
            } catch (ProfileNotFoundException $e) {
                $user_profile = new UserProfile(["user_id" => $user_id]);
            }
            return View::make('authentication::user.profile')->with(['user_profile' => $user_profile, 'profile_type' => Config::get('authentication::config_profile_type')]);
        } else {
            return Redirect::action('Palmabit\Authentication\Controllers\UserController@getList')
                ->withErrors(new MessageBag(['Non puoi accedere all\'area richiesta']));
        }
    }

    public function postEditProfile()
    {
        $input = Input::all();
        $service = new UserProfileService($this->v_p);

        try {
            $user_profile = $service->processForm($input, $this->sentry->getUser());
        } catch (GroupNotFoundException $e) {
            $errors = $service->getErrors();
            return Redirect::route("users.profile.edit", ["user_id" => $input['user_id']])->withInput()->withErrors($errors);
        } catch (PermissionException $e) {
            $errors = $service->getErrors();
            return Redirect::route("users.profile.edit", ["user_id" => $input['user_id']])->withInput()->withErrors($errors);
        }
        return Redirect::action('Palmabit\Authentication\Controllers\UserController@editProfile', ["user_id" => $user_profile->user_id])
            ->withMessage("Profilo modificato con successo.");
    }

    public function postSignup()
    {
        $input = Input::all();
        $service = new UserRegisterService();

        try {
            $service->register($input);
        } catch (PalmabitExceptionsInterface $e) {
            return Redirect::back()->withInput()->with(array('errors' => $service->getErrors()));
        }
        return Redirect::to(URL::action('Palmabit\Authentication\Controllers\UserController@signupSuccess'));
    }

    public function signupSuccess()
    {
        return View::make('authentication::auth.signupsuccess');
    }


}