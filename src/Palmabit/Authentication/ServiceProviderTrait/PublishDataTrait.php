<?php namespace Palmabit\Authentication\ServiceProviderTrait;

trait PublishDataTrait {

  protected function assetPublish() {
    $this->publishes([
      __DIR__ . '/../../../public' => public_path('packages/palmabit/authentication'),
    ]);

  }

  protected function configPublish() {
    $basePathPackage     = __DIR__ . '/../../config';
    $basePathDestination = 'package/palmabit/authentication';
    $this->publishes([
      $basePathPackage . '/sentry/config.php'                                 => config_path($basePathDestination . '/sentry/config.php'),
      $basePathPackage . 'config.php'                                         => config_path($basePathDestination . 'config.php'),
      $basePathPackage . 'config_profile_type.php'                            => config_path($basePathDestination . 'config_profile_type.php'),
      $basePathPackage . 'database.php'                                       => config_path($basePathDestination . 'database.php'),
      $basePathPackage . 'exclude_user_type.php'                              => config_path($basePathDestination . 'exclude_user_type.php'),
      $basePathPackage . 'field_to_send_for_email_new_registration_admin.php' => config_path($basePathDestination . 'field_to_send_for_email_new_registration_admin.php'),
      $basePathPackage . 'menu.php'                                           => config_path($basePathDestination . 'menu.php'),
      $basePathPackage . 'permissions.php'                                    => config_path($basePathDestination . 'permissions.php'),
      $basePathPackage . 'no_access_group.php'                                => config_path($basePathDestination . 'no_access_group.php'),
    ]);
  }

  protected function viewPublish() {

    $this->publishes([
      __DIR__ . '/../../views' => base_path('views/package/palmabit/authentication'),
    ]);
  }

}