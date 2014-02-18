<?php  namespace Palmabit\Authentication\Controllers; 
/**
 * Class UserController
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
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

    public function editUser($id = null)
    {
        try
        {
            $user = $this->r->find($id);
        }
        catch(UserNotFoundException $e)
        {
            $user = new User;
        }

        return View::make('authentication::user.edit')->with(["user" => $user]);
    }

    public function postEditUser()
    {
        try
        {
            $obj = $this->f->process(Input::all());
        }
        catch(PalmabitExceptionsInterface $e)
        {
            $errors = $this->f->getErrors();
            return Redirect::route("users.edit")->withInput()->withErrors($errors);
        }
        return Redirect::action('Palmabit\Authentication\Controllers\UserController@editUser',["id" => $obj->id])->withMessage("Utente modificato con successo.");
    }

    public function deleteUser()
    {
        try
        {
            $this->f->delete(Input::all());
        }
        catch(ModelNotFoundException $e)
        {
            $errors = $this->f->getErrors();
            return Redirect::action('Palmabit\Authentication\Controllers\UserController@getList')->withErrors($errors);
        }
        return Redirect::action('Palmabit\Authentication\Controllers\UserController@getList')->withMessage("Utente cancellato con successo.");
    }
} 