<?php  namespace Palmabit\Authentication\Tests; 

/**
 * Test SentryMenuFactoryTest
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
use Palmabit\Authentication\Classes\Menu\SentryMenuFactory;
use Palmabit\Authentication\Tests\TestCase;
use Config;

class SentryMenuFactoryTest extends TestCase {

    /**
     * @test
     **/
    public function it_creates_a_collection()
    {
        $config_arr = [
                [
                    "name" => "name1",
                    "link" => "link1",
                    "permission" => "permission1",
                    "route" => "route1",
                ],
                [
                    "name" => "name1",
                    "link" => "link1",
                    "permission" => "permission1",
                    "route" => "route2"
                ]
        ];
        Config::shouldReceive('get')->andReturn($config_arr);

        $collection = SentryMenuFactory::create();
        $this->assertInstanceOf('Palmabit\Authentication\Classes\Menu\MenuItemCollection', $collection);
        $items = $collection->getItemList();
        $this->assertEquals(2, count($items));
        $this->assertEquals("name1", $items[0]->getName());
        $this->assertEquals("route2", $items[1]->getRoute());
    }
}
 