<?php  namespace Palmabit\Authentication\Controllers; 
/**
 * Class UserController
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
use Illuminate\Support\MessageBag;
use Palmabit\Authentication\Repository\SentryUserRepository as Repo;
use Palmabit\Library\Form\FormModel;
use Palmabit\Authentication\Models\User;
use Palmabit\Authentication\Exceptions\UserNotFoundException;
use Palmabit\Authentication\Validators\UserValidator;
use Palmabit\Library\Exceptions\PalmabitExceptionsInterface;
use View, Input, Redirect;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function __construct(Repo $r, UserValidator $v)
    {
        $this->r = $r;
        $this->v = $v;
        $this->f = new FormModel($this->v, $this->r);
    }

    public function getList()
    {
        $users = $this->r->all();

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
} 