<?php namespace Palmabit\Authentication\Tests;
/**
 * Test TestCase
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
class TestCase extends \Orchestra\Testbench\TestCase  {

    public function setUp()
    {
        parent::setUp();
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
}
 