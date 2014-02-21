<?php  namespace Palmabit\Authentication\Repository; 
/**
 * Class GroupRepository
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
use Palmabit\Library\Repository\Interfaces\BaseRepositoryInterface;
use Palmabit\Authentication\Models\Group;
use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Palmabit\Authentication\Exceptions\UserNotFoundException as NotFoundException;
use App;
use Event;

class SentryGroupRepository implements BaseRepositoryInterface
{
    /**
     * Sentry instance
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
     * @throws \Palmabit\Authentication\Exceptions\UserNotFoundException
     */
    public function find($id)
    {
        try
        {
            $user = $this->sentry->findGroupById($id);
        }
        catch(GroupNotFoundException $e)
        {
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

}