<?php
namespace Palmabit\Authentication\Repository;

use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Cartalyst\Sentry\Users\UserExistsException as CartaUserExists;
use Illuminate\Support\Facades\Config;
use Palmabit\Authentication\Exceptions\PermissionException;
use Palmabit\Authentication\Repository\Interfaces\UserRepositoryInterface;
use Palmabit\Library\Repository\EloquentBaseRepository;
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
	 *
	 * @var
	 * @todo db test
	 */
	protected $sentry;

	/**
	 * Config reader
	 *
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
	 *
	 * @param array $input
	 * @return mixed
	 * @throws \Palmabit\Authentication\Exceptions\UserExistsException
	 * @todo db test
	 */
	public function create(array $input)
	{
		$data = array(
			"email"          => isset($input["email"]) ? $input["email"] : $input["copyEmail"],
			"password"       => $input["password"],
			"activated"      => $input["activated"],
			"preferred_lang" => isset($input["preferred_lang"]) ? $input["preferred_lang"] : Config::get('authentication::default_preferred_lang')
		);
		try {
			$user = $this->sentry->createUser($data);
		} catch (CartaUserExists $e) {
			throw new UserExistsException;
		}

		return $user;
	}

	/**
	 * Update a new object
	 *
	 * @param       id
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
	 *
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
		if (empty($data["password"])) {
			unset($data["password"]);
		}
	}

	/**
	 * Add a group to the user
	 *
	 * @param $user_id
	 * @param $group_id
	 * @return mixed|void
	 * @throws \Palmabit\Authentication\Exceptions\UserNotFoundException
	 */
	public function addGroup($user_id, $group_id)
	{
		try {
			$group = Group::findOrFail($group_id);
			$user = User::findOrFail($user_id);
			$user->addGroup($group);
		} catch (ModelNotFoundException $e) {
			throw new NotFoundException;
		}
	}

	/**
	 * Remove a group to the user
	 *
	 * @param $user_id
	 * @param $group_id
	 * @return mixed|void
	 * @throws \Palmabit\Authentication\Exceptions\UserNotFoundException
	 * @todo test
	 */
	public function removeGroup($user_id, $group_id)
	{
		try {
			$group = Group::findOrFail($group_id);
			$user = User::findOrFail($user_id);
			$user->removeGroup($group);
		} catch (ModelNotFoundException $e) {
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
	 * @return mixed
	 * @throws \Cartalyst\Sentry\Users\UserNotFoundException
	 */
	public function findFromGroupName($group_name)
	{
		$group = $this->sentry->findGroupByName($group_name);
		if (!$group) {
			throw new UserNotFoundException;
		}

		return $group->users;
	}

	/**
	 * @override
	 * @param array $input_filter
	 * @return mixed|void
	 */
	public function all(array $input_filter = null)
	{

		$user_table_name = 'users';
		$profile_table_name = 'user_profile';
		// merge tables
		$q = DB::table($user_table_name)
			->leftJoin($profile_table_name, $user_table_name . '.id', '=', $profile_table_name . '.user_id');


		//filter
		$q = $this->applyFilters($input_filter, $q, $user_table_name, $profile_table_name);
		$q = $this->createAllSelect($q, $user_table_name, $profile_table_name);

//        return $q->paginate($results_per_page);
		return $q;
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
		if ($input_filter) {
			foreach ($input_filter as $column => $value) {
				if ($value !== '') {
					switch ($column) {
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
		$q = $q->select($user_table_name . '.*', $profile_table_name . '.first_name', $profile_table_name . '.last_name', $profile_table_name .
			'.billing_address_zip',
			$profile_table_name . '.code');

		return $q;
	}

	public function excludeUserGroup($q, $toExclude = false, $groupsToExclude = [])
	{
		if ($toExclude) {
			$q = $q->join('users_groups', 'users.id', '=', 'users_groups.user_id')
				->join('groups', 'users_groups.group_id', '=', 'groups.id');
			foreach ($groupsToExclude as $groupExclude) {
				$groups = DB::table('groups')->where('name', '=', $groupExclude)->get();
				foreach ($groups as $group) {
					$q = $q->where('group_id', '!=', $group->id);
				}
			}
			$q = $q->groupBy('users.id');
		}

		return $q;
	}

	/**
	 * @param $query (builder)
	 * @return mixed
	 */
	public function paginate($query)
	{
		$results_per_page = $this->config->get('authentication::users_per_page');

		return $query->paginate($results_per_page);

	}

	/***
	 * @param $loggedUser
	 * @param $excludeConfig
	 * @return bool
	 */
	public function inGroupExlude($loggedUser, $excludeConfig)
	{
		$result = $excludeConfig['exclude'];
		foreach ($loggedUser->getGroups() as $groupUserLogged) {

			foreach ($excludeConfig['exclude_type'] as $groupToExclude) {
				if ($groupToExclude == $groupUserLogged->name) {
					$result = false;
				}
			}

			return $result;
		}
	}

	public function permissionToAddGroup($userId, $groupId)
	{
		$user = User::findOrFail($userId);
		$exludedGroup = Config::get('authentication::no_access_group');
		$groupToAssociate = Group::findOrFail($groupId);
		foreach ($user->getGroups() as $groupsAssociatedUser) {
			$excludedGroups = $exludedGroup[$groupsAssociatedUser->name];
			foreach ($excludedGroups as $excludedGroup) {
				if ($groupToAssociate->name == $excludedGroup) {
					throw new GroupNotFoundException;
				}
			}
		}
	}

	/**
	 * @param $loggedUser
	 * @param $userToEditId
	 * @throws \Palmabit\Authentication\Exceptions\PermissionException
	 */
	public function hasPermissionToEditUser($loggedUser, $userToEditId)
	{
		$exludedGroup = Config::get('authentication::no_access_group');
		$userToEdit = User::findOrNew($userToEditId);
		foreach ($loggedUser->getGroups() as $groupsAssociatedUser) {
			$excludedGroups = $exludedGroup[$groupsAssociatedUser->name];
			if ($this->checkUserGroups($userToEdit, $excludedGroups)) {
				if (!$this->checkAccessMyPage($loggedUser, $userToEditId)) {
					throw new PermissionException;
				}
			}
		}
	}

	/**
	 * @param $userToEdit
	 * @param $excludedGroups
	 * @return bool
	 */
	public function checkUserGroups($userToEdit, $excludedGroups)
	{
		foreach ($userToEdit->getGroups() as $userToEditAssociatedGroups) {
			if ($this->checkExcludedGroup($excludedGroups, $userToEditAssociatedGroups)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $excludedGroups
	 * @param $userToEditAssociatedGroups
	 * @return bool
	 */
	public function checkExcludedGroup($excludedGroups, $userToEditAssociatedGroups)
	{
		foreach ($excludedGroups as $excludedGroup) {
			if ($userToEditAssociatedGroups->name == $excludedGroup) {
				return true;
			}
		}

		return false;
	}

	public function checkEditablePermission($users, $loggedUser)
	{
		$exludedGroup = Config::get('authentication::no_access_group');
		foreach ($loggedUser->getGroups() as $groupLoggedUser) {
			$excludedGroups = $exludedGroup[$groupLoggedUser->name];
			$users = $this->permissionToEditUsers($users, $excludedGroups);
		}

		return $users;
	}

	/**
	 * @param $users
	 * @param $excludedGroups
	 */
	public function permissionToEditUsers($users, $excludedGroups)
	{
		foreach ($users as $index => $user) {
			$user = User::find($user->id);
			if ($this->checkUserGroups($user, $excludedGroups)) {
				$user->permissionToEdit = false;
			} else {
				$user->permissionToEdit = true;

			}
			$users[$index] = $user;
		}

		return $users;
	}

	public function checkAccessMyPage($loggedUser, $id)
	{
			if ($loggedUser->id == $id) {
				return true;
			}

		return false;
	}


	public function getLoggedUserFromDatabase()
	{
		$loggedUser = $this->sentry->getUser();
		$user = DB::table('users')->where('email', '=', $loggedUser->email);

		return $user;
	}

}

