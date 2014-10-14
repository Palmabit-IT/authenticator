<?php namespace Palmabit\Authentication;

use Illuminate\Support\ServiceProvider;
use Palmabit\Authentication\Install\Install;
use Palmabit\Authentication\Repository\EloquentPermissionRepository;
use Palmabit\Authentication\Repository\EloquentUserProfileRepository;
use Palmabit\Authentication\Repository\SentryGroupRepository;
use Palmabit\Authentication\Repository\SentryUserRepository;
use Palmabit\Library\Email\SwiftMailer;
use Palmabit\Authentication\Classes\SentryAuthenticator;
use Palmabit\Authentication\Helpers\SentryAuthenticationHelper;
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
   * @override
   * @return void
   */
  public function register() {
    $this->loadOtherProviders();
    $this->registerAliases();
  }

  /**
   * @override
   */
  public function boot() {
    $this->package('palmabit/authentication');

    $this->bindMailer();
    $this->bindAuthenticator();
    $this->bindAuthHelper();
    $this->bindRepositories();

    // include filters
    require __DIR__ . "/../../filters.php";
    // include routes.php
    require __DIR__ . "/../../routes.php";
    // include view composers
    require __DIR__ . "/../../composers.php";
    // include event subscribers
    require __DIR__ . "/../../subscribers.php";

    $this->setupConnection();
    $this->overrideValidationConnection();
    $this->overwriteSentryConfig();
    $this->overwriteWayFormConfig();
    $this->registerCommands();
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   * @override
   */
  public function provides() {
    return array ();
  }

  protected function overwriteSentryConfig() {
    $this->app['config']->getLoader()->addNamespace('cartalyst/sentry', __DIR__ . '/../../config/sentry');
  }

  protected function overwriteWayFormConfig() {
    $this->app['config']->getLoader()->addNamespace('form', __DIR__ . '/../../config/way-form');
  }

  protected function bindMailer() {
    $this->app->bind('palmamailer', function () {
      return new SwiftMailer;
    });
  }

  protected function bindAuthenticator() {
    $this->app->bind('authenticator', function () {
      return new SentryAuthenticator;
    });
  }

  protected function bindAuthHelper() {
    $this->app->bind('authentication_helper', function () {
      return new SentryAuthenticationHelper;
    });
  }

  protected function bindRepositories() {
    $this->app->bind('profile_repository', function () {
      return new EloquentUserProfileRepository;
    });
    $this->app->bind('user_repository', function () {
      return new SentryUserRepository;
    });
    $this->app->bind('group_repository', function () {
      return new SentryGroupRepository;
    });
    $this->app->bind('permission_repository', function () {
      return new EloquentPermissionRepository();
    });
  }

  protected function loadOtherProviders() {
    $this->app->register('Cartalyst\Sentry\SentryServiceProvider');
    $this->app->register('Way\Form\FormServiceProvider');
  }

  protected function registerAliases() {
    AliasLoader::getInstance()->alias("Sentry", 'Cartalyst\Sentry\Facades\Laravel\Sentry');
  }

  protected function setupConnection() {
    $connection = Config::get('authentication::database.default');

    if ($connection !== 'default') {
      $authenticator_conn = Config::get('authentication::database.connections.' . $connection);
    } else {
      $connection = Config::get('database.default');
      $authenticator_conn = Config::get('database.connections.' . $connection);
    }

    Config::set('database.connections.authentication', $authenticator_conn);
  }

  protected function overrideValidationConnection() {
    $this->app['validation.presence']->setConnection('authentication');
  }

  protected function registerCommands() {
    $this->registerInstallCommand();
  }

  protected function registerInstallCommand() {
    $this->app['authentication.install'] = $this->app->share(function ($app) {
      return new Install();
    });

    $this->commands('authentication.install');
  }
}