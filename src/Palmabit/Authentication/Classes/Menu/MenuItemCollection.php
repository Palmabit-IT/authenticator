<?php  namespace Palmabit\Authentication\Classes\Menu;

/**
 * Class MenuItemCollection

 */

use Palmabit\Authentication\Interfaces\MenuCollectionInterface;

class MenuItemCollection implements MenuCollectionInterface {
  protected $items;

  function __construct($items) {
    $this->items = $items;
  }

  public function getItemList() {
    return $this->items;
  }

  /**
   * Obtain the menu items that the current user can access
   *
   * @return array
   */
  public function getItemListAvailable() {
    $valid_items = [];
    foreach ($this->items as $item) {
      if ($item->havePermission()) {
        $valid_items[] = $item;
      }
    }
    return $valid_items;
  }
}