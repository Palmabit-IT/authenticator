<?php  namespace Palmabit\Authentication\Tests;

/**
 * Test MenuItemCollectionTest

 */
use Palmabit\Authentication\Classes\Menu\MenuItemCollection;
use Mockery as m;

class MenuItemCollectionTest extends TestCase
{

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     **/
    public function it_gets_items_available()
    {
        $mock_item1 = m::mock('StdClass')->shouldReceive('havePermission')->andReturn(true)->getMock();
        $mock_item2 = m::mock('StdClass')->shouldReceive('havePermission')->andReturn(false)->getMock();
        $items = [$mock_item1, $mock_item2];

        $collection = new MenuItemCollection($items);
        $item_valid = $collection->getItemListAvailable();
        $this->assertEquals(1, count($item_valid));
    }
}
 