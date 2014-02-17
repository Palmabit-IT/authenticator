<?php namespace Palmabit\Authentication;

use Illuminate\Support\ServiceProvider;
use Palmabit\Library\Email\SwiftMailer;
use Palmabit\Authentication\Classes\SentryAuthenticator;
use Illuminate\Foundation\AliasLoader;

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
        // register other service providers
        $this->app->register('Cartalyst\Sentry\SentryServiceProvider');
        $this->app->register('Way\Form\FormServiceProvider');
        // register aliases
        AliasLoader::getInstance()->alias("Sentry",'Cartalyst\Sentry\Facades\Laravel\Sentry');
    }

    public function boot()
    {
        $this->app->bind('palmamailer', function()
        {
            return new SwiftMailer;
        });
        $this->app->bind('authenticator', function()
        {
            return new SentryAuthenticator;
        });

        // include filters
        require_once __DIR__ . "/../../filters.php";
        // include routes.php
        require_once __DIR__ . "/../../routes.php";
        // include view composers
        require_once __DIR__ . "/../../composers.php";
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