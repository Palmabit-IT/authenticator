<?php  namespace Palmabit\Authentication\Tests; 

/**
 * Test EditableSubscriberTest
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
use Palmabit\Authentication\Events\EditableSubscriber;

class EditableSubscriberTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     **/
    public function it_check_if_is_editable()
    {
        $model = new \StdClass;
        $model->blocked = false;

        $sub = new EditableSubscriber();
    }

    /**
     * @test
     * @expectedException \Palmabit\Authentication\Exceptions\PermissionException
     **/
    public function it_check_if_es_editable_and_throw_new_exception()
    {
        $model = new \StdClass;
        $model->blocked = true;

        $sub = new EditableSubscriber();
        $sub->isEditable($model);
    }
}
 