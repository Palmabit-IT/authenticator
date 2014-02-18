<?php namespace Palmabit\Authentication;

use Illuminate\Support\ServiceProvider;
use Palmabit\Library\Email\SwiftMailer;
use Palmabit\Authentication\Classes\SentryAuthenticator;
use Illuminate\Foundation\AliasLoader;
use Config;

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

        $this->loadOtherProviders();

        $this->registerAliases();
    }

    public function boot()
    {
        $this->bindMailer();

        $this->bindAuthenticator();

        // include filters
        require __DIR__ . "/../../filters.php";
        // include routes.php
        require __DIR__ . "/../../routes.php";
        // include view composers
        require __DIR__ . "/../../composers.php";

        $this->overwriteSentryConfig();

        $this->overwriteWayFormConfig();
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

    protected function overwriteSentryConfig()
    {
        $this->app['config']->getLoader()->addNamespace('cartalyst/sentry', __DIR__ . '/../../config/sentry');
    }

    protected function overwriteWayFormConfig()
    {
        $this->app['config']->getLoader()->addNamespace('form', __DIR__ . '/../../config/way-form');
    }

    protected function bindMailer()
    {
        $this->app->bind('palmamailer', function () {
            return new SwiftMailer;
        });
    }

    protected function bindAuthenticator()
    {
        $this->app->bind('authenticator', function () {
            return new SentryAuthenticator;
        });
    }

    protected function loadOtherProviders()
    {
        $this->app->register('Cartalyst\Sentry\SentryServiceProvider');
        $this->app->register('Way\Form\FormServiceProvider');
    }

    protected function registerAliases()
    {
        AliasLoader::getInstance()->alias("Sentry", 'Cartalyst\Sentry\Facades\Laravel\Sentry');
    }

}