<?php  namespace Palmabit\Authentication\Models;

/**
 * Class Permission
 */
class Permission extends BaseModel {
  protected $table = "permission";

  protected $fillable = ["description", "permission", "blocked"];

  protected $guarded = ["id"];

  /**
   * Prepend a prefix for  permission mainly to force it to
   * associative array for Sentry
   *
   * @param $value
   */
  public function setPermissionAttribute($value) {
    $this->attributes["permission"] = ($value[0] != "_") ? "_{$value}" : $value;
  }
} 