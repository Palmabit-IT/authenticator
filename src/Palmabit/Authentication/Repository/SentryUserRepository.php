<?php
namespace Palmabit\Authentication\Repository;
/**
 * Class UserRepository
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */
use Cartalyst\Sentry\Users\UserExistsException as CartaUserExists;
use Palmabit\Authentication\Repository\Interfaces\UserRepositoryInterface;
use Palmabit\Library\Repository\EloquentBaseRepository;
use Palmabit\Library\Repository\Interfaces\BaseRepositoryInterface;
use Palmabit\Authentication\Exceptions\UserNotFoundException as NotFoundException;
use Palmabit\Authentication\Exceptions\UserExistsException as UserExistsException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Palmabit\Authentication\Models\User;
use Palmabit\Authentication\Models\Group;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Event;

class SentryUserRepository extends EloquentBaseRepository implements UserRepositoryInterface
{
    /**
     * Sentry instance
     * @var
     * @todo db test
     */
    protected $sentry;

    public function __construct($factory = null)
    {
        $this->sentry = \App::make('sentry');
        Event::listen('repository.updating', 'Palmabit\Authentication\Services\UserRegisterService@sendActivationEmailToClient');
        return parent::__construct(new User);
    }

    /**
     * Create a new object
     * @return mixed
     * @override
     * @todo db test
     */
    public function create(array $input)
    {
        $data = array(
                "email" => $input["email"],
                "password" => $input["password"],
                "activated" => $input["activated"],
                "new_user" => $input["new_user"],
                "first_name" => $input["first_name"],
                "last_name" => $input["last_name"],
                "imported" => isset($input["imported"]) ? 1 : 0
        );
        try
        {
            $user = $this->sentry->createUser($data);
        }
        catch(CartaUserExists $e)
        {
            throw new UserExistsException;
        }

        return $user;
    }

    /**
     * Update a new object
     * @param id
     * @param array $data
     * @throws \Palmabit\Authentication\Exceptions\UserNotFoundException
     * @return mixed
     * @override
     * @todo db test
     */
    public function update($id, array $data)
    {
        $this->ClearEmptyPassword($data);
        $obj = $this->find($id);
        Event::fire('repository.updating', [$obj, $data]);
        $obj->update($data);
        return $obj;
    }

    /**
     * Deletes a new object
     * @param $id
     * @return mixed
     * @override
     * @todo db test
     */
    public function delete($id)
    {
        $obj = $this->find($id);
        Event::fire('repository.deleting', [$obj]);
        return $obj->delete();
    }

    /**
     * Obtains all models
     * @return mixed
     * @override
     * @todo db test
     */
    public function all()
    {
        $per_page_admin = 45;
        return User::paginate($per_page_admin);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function ClearEmptyPassword(array &$data)
    {
        if (empty($data["password"])) unset($data["password"]);
    }

    /**
     * Add a group to the user
     * @param $id group id
     * @throws \Palmabit\Authentication\Exceptions\UserNotFoundException
     * @todo test
     */
    public function addGroup($user_id, $group_id)
    {
        try
        {
            $group = Group::findOrFail($group_id);
            $user = User::findOrFail($user_id);
            $user->addGroup($group);
        }
        catch(ModelNotFoundException $e)
        {
            throw new NotFoundException;
        }
    }
    /**
     * Remove a group to the user
     * @param $id group id
     * @throws \Palmabit\Authentication\Exceptions\UserNotFoundException
     * @todo test
     */
    public function removeGroup($user_id, $group_id)
    {
        try
        {
            $group = Group::findOrFail($group_id);
            $user = User::findOrFail($user_id);
            $user->removeGroup($group);
        }
        catch(ModelNotFoundException $e)
        {
            throw new NotFoundException;
        }
    }

    /**
     * Activates a user
     *
     * @param integer id
     * @return mixed
     */
    public function activate($id)
    {
        // TODO: Implement activate() method.
    }

    /**
     * Deactivate a user
     *
     * @param $id
     * @return mixed
     */
    public function deactivate($id)
    {
        // TODO: Implement deactivate() method.
    }

    /**
     * Suspends a user
     *
     * @param $id
     * @param $duration in minutes
     * @return mixed
     */
    public function suspend($id, $duration)
    {
        // TODO: Implement suspend() method.
    }

    /**
     * Obtain a list of user from a given group
     *
     * @param String $group_name
     * @throws \Palmabit\Authentication\Exceptions\UserNotFoundException
     * @return mixed
     */
    public function findFromGroupName($group_name)
    {
        $group = $this->sentry->findGroupByName($group_name);
        if(! $group) throw new UserNotFoundException;

        return $group->users;
    }

    /**
     * Obtain a list of user filterd by status (active) or type (new) or gruop
     *
     * @param String $filter
     * @throws \Palmabit\Authentication\Exceptions\UserNotFoundException
     * @return mixed
     */
    public function findFromAttrName($status)
    {

        switch ($status)
        {
            case 'new':
                $users = $this->findByNewUser($status);
                break;
            case 'inregola':
                $users = $this->findByActive($status);
                break;
            case 'noninregola':
                $users = $this->findByNonActive($status);
                break;
            default:
                $users = $this->all();
                break;
        }

        return $users;
    }

    /**
     * @param $login_name
     * @throws \Jacopo\Authentication\Exceptions\UserNotFoundException
     */
    public function findByLogin($login_name)
    {
        try
        {
            $user = $this->sentry->findUserByLogin($login_name);
        }
        catch(UserNotFoundException $e)
        {
            throw new NotFoundException;
        }

        return $user;
    }

    /**
     * @param $active
     */
    public function findByActive($active)
    {
        $user = $this->model->where('activated',1)->paginate(20);
        return $user;
    }

    /**
     * @param $active
     */
    public function findByNonActive($active)
    {
        $user = $this->model->where('activated',0)->paginate(20);
        return $user;
    }

    /**
     * @param $status
     */
    public function findByNewUser($status)
    {
        $user = $this->model->where('new_user',1)->paginate(20);
        return $user;
    }

}