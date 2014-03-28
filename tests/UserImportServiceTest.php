<?php  namespace Palmabit\Authentication\Tests; 
use Mockery as m;
use Palmabit\Authentication\Services\UserImport\UserImportService;
use App;
/**
 * Test UserImportServiceTest
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
class UserImportServiceTest extends DbTestCase {

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     **/
    public function it_import_csv_data()
    {
        $mock_file_input = m::mock('StdClass')->shouldReceive('getRealPath')
            ->once()
            ->andReturn(__DIR__.'/test_file.csv')
            ->getMock();
        $input = ["file" => $mock_file_input];

        $service = new UserImportService();

        $service->importCsv($input);

        $users = App::make('user_repository')->all();
        $this->assertEquals(2, $users->count());
    }
}
 