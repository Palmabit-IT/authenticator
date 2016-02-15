<?php   namespace Palmabit\Authentication\ServiceProviderTrait;

use Palmabit\Authentication\Classes\SentryAuthenticator;
use Palmabit\Authentication\Helpers\SentryAuthenticationHelper;
use Palmabit\Authentication\Repository\EloquentPermissionRepository;
use Palmabit\Authentication\Repository\EloquentUserProfileRepository;
use Palmabit\Authentication\Repository\SentryGroupRepository;
use Palmabit\Authentication\Repository\SentryUserRepository;
use Palmabit\Library\Email\SwiftMailer;

trait BindApplicationTrait {

  protected function binderApplication() {
    $this->bindMailer();
    $this->bindAuthenticator();
    $this->bindAuthHelper();
    $this->bindRepositories();

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

}