<?php  namespace Palmabit\Authentication\Interfaces;

/**
 * Interface PermissionProfileHelperInterface
 */
interface PermissionProfileHelperInterface {

  /**
   * Check if the current user has permission to edit the profile
   *
   * @param $user_id
   * @return mixed
   */
  public function checkProfileEditPermission($user_id);

  /**
   * Obtain the user email that needs to be notificated on registration
   *
   * @return array
   */
  public function getNotificationRegistrationUsersEmail();
}