<?php  namespace Palmabit\Authentication\Tests;

use Artisan;
use Closure;
use DB;

class DbTestCase extends TestCase {
  protected $times = 1;

  public function setUp() {
    parent::setUp();

    $artisan = $this->app->make('artisan');

    $this->populateDB($artisan);
  }

  /**
   * @test
   **/
  public function it_mock_test() {
    $this->assertTrue(true);
  }

  /**
   * @deprecated used for old mysql test
   */
  protected function cleanDb() {
    $manager = DB::getDoctrineSchemaManager();
    $tables = $manager->listTableNames();

    DB::Statement("SET FOREIGN_KEY_CHECKS=0");
    foreach ($tables as $key => $table) {
      DB::Statement("DROP TABLE " . $table . "");
    }
    DB::Statement("SET FOREIGN_KEY_CHECKS=1");
  }

  /**
   * Define environment setup.
   *
   * @param  \Illuminate\Foundation\Application $app
   * @return void
   */
  protected function getEnvironmentSetUp($app) {
    // reset base path to point to our package's src directory
    $app['path.base'] = __DIR__ . '/../src';

    $mysql_conn = array (
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'palmabit_base_test',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
    );

    $sqlite_conn = array (
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
    );

    $app['config']->set('database.default', 'testbench');
    $app['config']->set('database.connections.testbench', $sqlite_conn);
  }

  /**
   * @param $artisan
   */
  protected function populateDB($artisan) {
    $artisan->call('migrate', [
            "--database" => "testbench", '--path' => '../src/migrations', '--seed' => '']);
  }


  /**
   * @param       $class_name
   * @param mixed $extra
   * @return array
   */
  protected function make($class_name, $extra = []) {
    $created_objs = new Collection();

    while ($this->times--) {
      $extra_data = ($extra instanceof Closure) ? $extra() : $extra;
      $stub_data = array_merge($this->getModelStub(), $extra_data);
      $created_objs->push($class_name::create($stub_data));
    }

    $this->resetTimes();

    return $created_objs;
  }

  protected function getModelStub() {
    throw new BadMethodCallException("You need to implement getModelStub method in your own test class.");
  }

  protected function times($count) {
    $this->times = $count;

    return $this;
  }

  protected function resetTimes() {
    $this->times = 1;
  }
} 