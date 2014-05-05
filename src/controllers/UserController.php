<?php  namespace Palmabit\Authentication\Controllers;
/**
 * Class UserController
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
use Illuminate\Support\MessageBag;
use Palmabit\Authentication\Exceptions\ProfileNotFoundException;
use Palmabit\Authentication\Models\UserProfile;
use Palmabit\Authentication\Repository\SentryUserRepository as Repo;
use Palmabit\Authentication\Services\UserRegisterService;
use Palmabit\Authentication\Validators\UserImportValidator;
use Palmabit\Authentication\Validators\UserSignupValidator;
use Palmabit\Library\Form\FormModel;
use Palmabit\Authentication\Models\User;
use Palmabit\Authentication\Exceptions\UserNotFoundException;
use Palmabit\Authentication\Validators\UserValidator;
use Palmabit\Authentication\Validators\UserProfileValidator;
use Palmabit\Library\Exceptions\PalmabitExceptionsInterface;
use View, Input, Redirect, App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Palmabit\Authentication\Services\UserProfileService;
use Palmabit\Authentication\Services\UserImport\UserImportService;
use L, URLT;

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
    protected $repository_profile;

    /**
     * Profile repository
     */
    protected $r_p;

    public function __construct(Repo $r, UserValidator $v, UserSignupValidator $vs)
    {
        $this->r = $r;
        $this->v = $v;
        $this->f = new FormModel($this->v, $this->r);
        $this->v_s = $vs;
        $this->repository_profile = App::make('profile_repository');
    }

    public function getList()
    {
        $users = $this->r->all(Input::all());

        return View::make('authentication::user.list')->with(["users" => $users]);
    }

    public function editUser()
    {
        try
        {
            $user = $this->r->find(Input::get('id'));
        }
        catch(PalmabitExceptionsInterface $e)
        {
            $user = new User;
        }

        return View::make('authentication::user.edit')->with(["user" => $user]);
    }

    public function postEditUser()
    {
        $id = Input::get('id');

        try
        {
            $obj = $this->f->process(Input::all());
        }
        catch(PalmabitExceptionsInterface $e)
        {
            $errors = $this->f->getErrors();
            // passing the id incase fails editing an already existing item
            return Redirect::route("users.edit", $id ? ["id" => $id]: [])->withInput()->withErrors($errors);
        }

        return Redirect::action('Palmabit\Authentication\Controllers\UserController@editUser',["id" => $obj->id])->withMessage("Utente modificato con successo.");
    }

    public function editProfile()
    {
        $user_id = Input::get('user_id');

        try
        {
            $user_profile = $this->repository_profile->getFromUserId($user_id);
        }
        catch(UserNotFoundException $e)
        {
            return Redirect::action('Palmabit\Authentication\Controllers\UserController@getList')->withErrors(new MessageBag(['model' => 'Utente non presente.']));
        }
        catch(ProfileNotFoundException $e)
        {
            $user_profile = new UserProfile(["user_id" => $user_id]);
        }

        return View::make('authentication::user.profile')->with(['user_profile' => $user_profile]);
    }

    public function postEditProfile()
    {
        $input = Input::all();
        $service = new UserProfileService($this->v_p);

        try
        {
            $user_profile = $service->processForm($input);
        }
        catch(PalmabitExceptionsInterface $e)
        {
            $errors = $service->getErrors();
            return Redirect::route("users.profile.edit", ["user_id" => $input['user_id'] ])->withInput()->withErrors($errors);
        }
        return Redirect::action('Palmabit\Authentication\Controllers\UserController@editProfile',["user_id" => $user_profile->user_id])->withMessage("Profilo modificato con successo.");
    }

    public function deleteUser()
    {
        try
        {
            $this->f->delete(Input::all());
        }
        catch(PalmabitExceptionsInterface $e)
        {
            $errors = $this->f->getErrors();
            return Redirect::action('Palmabit\Authentication\Controllers\UserController@getList')->withErrors($errors);
        }
        return Redirect::action('Palmabit\Authentication\Controllers\UserController@getList')->withMessage("Utente cancellato con successo.");
    }

    public function addGroup()
    {
        $user_id = Input::get('id');
        $group_id = Input::get('group_id');

        if( ! App::make('authenticator')->getLoggedUser()->hasAccess("_super_admin") ) return;

        try
        {
            $this->r->addGroup($user_id, $group_id);
        }
        catch(PalmabitExceptionsInterface $e)
        {
            return Redirect::action('Palmabit\Authentication\Controllers\UserController@editUser', ["id" => $user_id])->withErrors(new MessageBag(["name" => "Gruppo non presente."]));
        }
        return Redirect::action('Palmabit\Authentication\Controllers\UserController@editUser',["id" => $user_id])->withMessage("Gruppo aggiunto con successo.");
    }

    public function deleteGroup()
    {
        $user_id = Input::get('id');
        $group_id = Input::get('group_id');

        try
        {
            $this->r->removeGroup($user_id, $group_id);
        }
        catch(PalmabitExceptionsInterface $e)
        {
            return Redirect::action('Palmabit\Authentication\Controllers\UserController@editUser', ["id" => $user_id])->withErrors(new MessageBag(["name" => "Gruppo non presente."]));
        }
        return Redirect::action('Palmabit\Authentication\Controllers\UserController@editUser',["id" => $user_id])->withMessage("Gruppo cancellato con successo.");
    }

    public function getSignupUser()
    {
        return View::make('authentication::user.signup');
    }

    public function postSignupUser()
    {
        $input = Input::all();
        $service = new UserRegisterService();

        try
        {
            $service->register($input);
        }
        catch(PalmabitExceptionsInterface $e)
        {
            return Redirect::back()->withInput()->withErrors($service->getErrors())->with(array('errorSignup' => $service->getErrors()));
        }

        return Redirect::back()->withMessage(L::t('Your request will be process in few hours. As soon as possible you receive a confirmation email'));

    }

    public function import()
    {
        return View::make('authentication::user.import');
    }

    /**
     * @return mixed
     * @todo refactor, sposta input file in service e poi fixa test
     * @todo fix validation in service invece che nel controller e adda custom mime rule
     */
    public function postImport()
    {
        $service = new UserImportService();

        $validator = new UserImportValidator();
        if( ! $validator->validate(Input::all())) return Redirect::action('Palmabit\Authentication\Controllers\UserController@import')->withErrors($validator->getErrors());
        if( Input::file('file')->getClientOriginalExtension() != 'csv') return Redirect::action('Palmabit\Authentication\Controllers\UserController@import')->withErrors( new MessageBag(["file"=> "Solo file csv."]));
        try
        {
            $service->importCsv(['file' => Input::file('file')]);
        }
        catch(\Exception $e)
        {
            return Redirect::action('Palmabit\Authentication\Controllers\UserController@import')->withErrors(["file" => $e->getMessage()]);
        }

        return Redirect::action('Palmabit\Authentication\Controllers\UserController@import')->withMessage('Importazione effettuata con successo.');
    }

}