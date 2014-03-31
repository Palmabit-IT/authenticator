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
use Event, App, DB;

class SentryUserRepository extends EloquentBaseRepository implements UserRepositoryInterface
{
    /**
     * Sentry instance
     * @var
     * @todo db test
     */
    protected $sentry;

    /**
     * Config reader
     * @var null
     */
    protected $config;

    public function __construct($config = null)
    {
        $this->config = $config ? $config : App::make('config');
        $this->sentry = \App::make('sentry');
        Event::listen('repository.updating', 'Palmabit\Authentication\Services\UserRegisterService@sendActivationEmailToClient');
        return parent::__construct(new User);
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
                "activated" => $input["activated"],
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
     * @todo db test
     */
    public function delete($id)
    {
        $obj = $this->find($id);
        Event::fire('repository.deleting', [$obj]);
        return $obj->delete();
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
     * @override
     * @param array $input_filter
     * @return mixed|void
     */
    public function all(array $input_filter = null)
    {
        $results_per_page = $this->config->get('authentication::users_per_page');
        $user_table_name = 'users';
        $profile_table_name = 'user_profile';
        // merge tables
        $q = DB::table($user_table_name)
            ->leftJoin($profile_table_name,$user_table_name.'.id', '=', $profile_table_name.'.user_id');
        // filter data
        $q = $this->applyFilters($input_filter, $q, $user_table_name, $profile_table_name);

        $q = $this->createAllSelect($q, $user_table_name, $profile_table_name);

        return $q->paginate($results_per_page);
    }

    /**
     * @param array $input_filter
     * @param       $q
     * @param       $user_table
     * @param       $profile_table
     * @return mixed
     */
    protected function applyFilters(array $input_filter = null, $q, $user_table, $profile_table)
    {
        if($input_filter) foreach ($input_filter as $column => $value) {
            if( $value !== '') switch ($column) {
                case 'activated':
                    $q = $q->where($user_table . '.activated', '=', $value);
                    break;
                case 'email':
                    $q = $q->where($user_table . '.email', 'LIKE', "%{$value}%");
                    break;
                case 'first_name':
                    $q = $q->where($profile_table . '.first_name', 'LIKE', "%{$value}%");
                    break;
                case 'last_name':
                    $q = $q->where($profile_table . '.last_name', 'LIKE', "%{$value}%");
                    break;
                case 'billing_address_zip':
                    $q = $q->where($profile_table . '.billing_address_zip', '=', $value);
                    break;
                case 'code':
                    $q = $q->where($profile_table . '.code', '=', $value);
                    break;
            }
        }

        return $q;
    }

    /**
     * @param $q
     * @param $user_table_name
     * @param $profile_table_name
     * @return mixed
     */
    protected function createAllSelect($q, $user_table_name, $profile_table_name)
    {
        $q = $q->select($user_table_name . '.*', $profile_table_name . '.first_name', $profile_table_name . '.last_name', $profile_table_name . '.billing_address_zip', $profile_table_name . '.code');

        return $q;
    }
}