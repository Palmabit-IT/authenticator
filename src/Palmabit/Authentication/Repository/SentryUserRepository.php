<?php
namespace Palmabit\Authentication\Repository;
/**
 * Class UserRepository
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */
use Palmabit\Authentication\Repository\Interfaces\UserRepositoryInterface;
use Palmabit\Library\Repository\Interfaces\BaseRepositoryInterface;
use Palmabit\Authentication\Exceptions\UserNotFoundException as NotFoundException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Palmabit\Authentication\Models\User;
use Palmabit\Authentication\Models\Group;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SentryUserRepository implements BaseRepositoryInterface, UserRepositoryInterface
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
    }

    /**
     * Create a new object
     * @return mixed
     * @todo db test
     */
    public function create(array $input)
    {
        $data = array(
                "email" => $input["email"],
                "password" => $input["password"],
                "first_name" => $input["first_name"],
                "last_name" => $input["last_name"],
                "activated" => $input["activated"],
        );
        $user = $this->sentry->createUser($data);
        return $user->with('groups');
    }

    /**
     * Update a new object
     * @param id
     * @param array $data
     * @throws \Palmabit\Authentication\Exceptions\UserNotFoundException
     * @return mixed
     * @todo db test
     */
    public function update($id, array $data)
    {
        $this->ClearEmptyPassword($data);
        $obj = $this->find($id);
        $obj->update($data);
        return $obj;
    }

    /**
     * Deletes a new object
     * @param $id
     * @return mixed
     * @todo db test
     */
    public function delete($id)
    {
        $obj = $this->find($id);
        return $obj->delete();
    }

    /**
     * Find a model by his id
     * @param $id
     * @return mixed
     * @throws \Palmabit\Authentication\Exceptions\UserNotFoundException
     * @todo db test
     */
    public function find($id)
    {
       try
       {
            $user = $this->sentry->findUserById($id);
       }
       catch(UserNotFoundException $e)
       {
            throw new NotFoundException;
       }

       return $user;
    }

    /**
     * Obtains all models
     * @return mixed
     * @todo db test
     */
    public function all()
    {
        return User::all();
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
}