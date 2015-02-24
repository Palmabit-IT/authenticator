<?php namespace Palmabit\Authentication\Tests;

/**
 * Test TestCase

 */
class TestCase extends \Orchestra\Testbench\TestCase
{

    protected $fake;

    public function setUp()
    {
        parent::setUp();

        $this->fake = \Faker\Factory::create();
        require_once __DIR__ . "/../src/routes.php";
    }

    protected function getPackageProviders()
    {
        return [
            'Cartalyst\Sentry\SentryServiceProvider',
            'Palmabit\Authentication\AuthenticationServiceProvider',
        ];
    }

    protected function getPackageAliases()
    {
        return [
            'Sentry' => 'Cartalyst\Sentry\Facades\Laravel\Sentry',
        ];
    }

    /**
     * @test
     **/
    public function dummy()
    {
    }
}
 