<?php  namespace Palmabit\Authentication\Repository;

/**
 * Class GroupRepository
 */
use Palmabit\Authentication\Repository\Interfaces\UserGroupRepositoryInterface;
use Palmabit\Library\Repository\Interfaces\BaseRepositoryInterface;
use Palmabit\Authentication\Models\Group;
use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Palmabit\Authentication\Exceptions\GroupNotFoundException as NotFoundException;
use App;
use Event;

class SentryGroupRepository implements BaseRepositoryInterface, UserGroupRepositoryInterface
{
    /**
     * Sentry instance
     *
     * @var
     * @todo db test
     */
    protected $sentry;

    public function __construct($factory = null)
    {
        $this->sentry = App::make('sentry');
    }

    /**
     * Create a new object
     *
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->sentry->createGroup($data);
    }

    /**
     * Update a new object
     *
     * @param       id
     * @param array $data
     * @return mixed
     * @throw \Palmabit\Authentication\Exceptions\GroupNotFoundException
     */
    public function update($id, array $data)
    {
        $obj = $this->find($id);
        Event::fire('repository.updating', [$obj]);
        $obj->update($data);
        return $obj;
    }

    /**
     * Deletes a new object
     *
     * @param $id
     * @return mixed
     * @throw \Palmabit\Authentication\Exceptions\GroupNotFoundException
     */
    public function delete($id)
    {
        $obj = $this->find($id);
        Event::fire('repository.deleting', [$obj]);
        return $obj->delete();
    }

    /**
     * Find a model by his id
     *
     * @param $id
     * @return mixed
     * @throw \Palmabit\Authentication\Exceptions\GroupNotFoundException
     */
    public function find($id)
    {
        try {
            $user = $this->sentry->findGroupById($id);
        } catch (GroupNotFoundException $e) {
            throw new NotFoundException;
        }

        return $user;
    }

    /**
     * Obtains all models
     *
     * @return mixed
     */
    public function all()
    {
        return Group::all();
    }

    /**
     * @param $name
     * @throw \Palmabit\Authentication\Exceptions\GroupNotFoundException
     */
    public function findByName($name)
    {
        try {
            $group = $this->sentry->findGroupByName($name);
        } catch (GroupNotFoundException $e) {
            throw new  NotFoundException;
        }

        return $group;
    }
}