<?php  namespace Palmabit\Authentication\Interfaces;

/**
 * Interface MenuCollectionInterface
 */
interface MenuCollectionInterface
{
    /**
     * Obtain all the menu items
     *
     * @return \Palmabit\Authentication\Classes\MenuItem
     */
    public function getItemList();

    /**
     * Obtain the menu items that the current user can access
     *
     * @return mixed
     */
    public function getItemListAvailable();
}