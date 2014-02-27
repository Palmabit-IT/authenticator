<?php  namespace Palmabit\Authentication\Repository;
use Palmabit\Authentication\Models\UserProfile;
use Palmabit\Library\Repository\EloquentBaseRepository;
use Palmabit\Library\Repository\Interfaces\BaseRepositoryInterface;

/**
 * Class EloquentUserProfileRepository
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */
class EloquentUserProfileRepository extends EloquentBaseRepository
{
    /**
     * We use the userprofile as a model
     */
    public function __construct()
    {
        return parent::__construct(new UserProfile);
    }
}