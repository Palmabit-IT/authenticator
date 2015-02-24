<?php  namespace Palmabit\Authentication\Controllers;

/**
 * Class PermissionController
 *
 */
use Palmabit\Authentication\Repository\EloquentPermissionRepository as Repo;
use Palmabit\Library\Form\FormModel;
use Palmabit\Authentication\Models\Permission;
use Palmabit\Authentication\Validators\PermissionValidator;
use Palmabit\Library\Exceptions\PalmabitExceptionsInterface;
use View, Input, Redirect;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionController extends \BaseController
{
    /**
     * @var \Palmabit\Authentication\Repository\PermissionGroupRepository
     */
    protected $r;
    /**
     * @var \Palmabit\Authentication\Validators\PermissionValidator
     */
    protected $v;

    public function __construct(Repo $r, PermissionValidator $v)
    {
        $this->r = $r;
        $this->v = $v;
        $this->f = new FormModel($this->v, $this->r);
    }

    public function getList()
    {
        $objs = $this->r->all();

        return View::make('authentication::permission.list')->with(["permissions" => $objs]);
    }

    public function editPermission()
    {

        try {
            $obj = $this->r->find(Input::get('id'));
        } catch (PalmabitExceptionsInterface $e) {
            $obj = new Permission;
        }
        return View::make('authentication::permission.edit')->with(["permission" => $obj]);
    }

    public function postEditPermission()
    {
        $id = Input::get('id');
        if (is_null($id)) {
            try {
                $permission = $this->r->getPermission($id);
                $this->r->checkIsNotSuperadminOrAdmin($permission);
            } catch (PalmabitExceptionsInterface $e) {
                return Redirect::back()->withInput()->withErrors("Non e' possibile modificare questo permesso");
            }
        }
        try {
            $obj = $this->f->process(Input::all());
        } catch (PalmabitExceptionsInterface $e) {
            $errors = $this->f->getErrors();
            // passing the id incase fails editing an already existing item
            return Redirect::route("permission.edit", $id ? ["id" => $id] : [])->withInput()->withErrors($errors);
        }

        return Redirect::action('Palmabit\Authentication\Controllers\PermissionController@editPermission', ["id" => $obj->id])->withMessage("Permesso modificato con successo.");
    }

    public function deletePermission()
    {
        $id = Input::get('id');
        try {
            $permission = $this->r->getPermission($id);
            $this->r->checkIsNotSuperadminOrAdmin($permission);
        } catch (PalmabitExceptionsInterface $e) {
            return Redirect::back()->withInput()->withErrors("Non e' possibile eliminare questo permesso");
        }
        try {
            $this->f->delete(Input::all());
        } catch (PalmabitExceptionsInterface $e) {
            $errors = $this->f->getErrors();
            return Redirect::action('Palmabit\Authentication\Controllers\PermissionController@getList')->withErrors($errors);
        }
        return Redirect::action('Palmabit\Authentication\Controllers\PermissionController@getList')->withMessage("Permesso cancellato con successo.");
    }
} 