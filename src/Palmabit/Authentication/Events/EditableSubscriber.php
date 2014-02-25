<?php  namespace Palmabit\Authentication\Events; 
/**
 * Class EbitableSubscriber
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */

use Palmabit\Authentication\Exceptions\PermissionException;

class EditableSubscriber
{
    protected $editable_field = "editable";
    /**
     * Check if the object is editable
     */
    public function isEditable($object)
    {
        if($object->editable != true) throw new PermissionException;
    }

    /**
     * Register the various event to the subscriber
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen('repository.deleting', 'Palmabit\Authentication\Events\EditableSubscriber@isEditable');

        $events->listen('repository.updating', 'Palmabit\Authentication\Events\EditableSubscriber@isEditable');
    }

} 