<?php   namespace Palmabit\Authentication\ServiceProviderTrait;

trait OverrideTrait {
  protected function overwriteSentryConfig() {
    $this->app[ 'config' ]->getLoader()->addNamespace('cartalyst/sentry', __DIR__ . '/../../config/sentry');
  }

  protected function overrideValidationConnection() {
    $this->app[ 'validation.presence' ]->setConnection('authentication');
  }
}