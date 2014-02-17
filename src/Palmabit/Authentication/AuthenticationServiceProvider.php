<?php namespace Palmabit\Authentication;

use Illuminate\Support\ServiceProvider;
use Palmabit\Library\Email\SwiftMailer;
use Palmabit\Authentication\Classes\SentryAuthenticator;

class AuthenticationServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->package('palmabit/authentication');
        $this->app->register('Cartalyst\Sentry\SentryServiceProvider');
    }

    public function boot()
    {
        // include routes.php
        require_once __DIR__ . "/../../routes.php";

        $this->app->bind('palmamailer', function()
        {
            return new SwiftMailer;
        });
        $this->app->bind('authenticator', function()
        {
            return new SentryAuthenticator;
        });
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}