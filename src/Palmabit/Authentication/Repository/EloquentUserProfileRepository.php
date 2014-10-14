<?php  namespace Palmabit\Authentication\Repository;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Palmabit\Authentication\Exceptions\UserNotFoundException;
use Palmabit\Authentication\Exceptions\ProfileNotFoundException;
use Palmabit\Authentication\Models\User;
use Palmabit\Authentication\Models\UserProfile;
use Palmabit\Authentication\Repository\Interfaces\UserProfileRepositoryInterface;
use Palmabit\Library\Repository\EloquentBaseRepository;
use Palmabit\Library\Repository\Interfaces\BaseRepositoryInterface;

/**
 * Class EloquentUserProfileRepository
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */
class EloquentUserProfileRepository extends EloquentBaseRepository implements UserProfileRepositoryInterface {
  /**
   * We use the user profile as a model
   */
  public function __construct() {
    return parent::__construct(new UserProfile);
  }

  public function getFromUserId($user_id) {
    // checks if the user exists
    try {
      User::findOrFail($user_id);
    } catch (ModelNotFoundException $e) {
      throw new UserNotFoundException;
    }
    // gets the profile
    $profile = $this->model->where('user_id', '=', $user_id)
                           ->get();

    // check if the profile exists
    if ($profile->isEmpty()) {
      throw new ProfileNotFoundException;
    }

    return $profile->first();
  }


  public function attachEmptyProfile($user) {
    if ($this->hasAlreadyAnUserProfile($user)) {
      return;
    }

    return $this->create(["user_id" => $user->id]);
  }

  /**
   * @param $user
   * @return mixed
   */
  protected function hasAlreadyAnUserProfile($user) {
    return $user->user_profile()->count();
  }
}