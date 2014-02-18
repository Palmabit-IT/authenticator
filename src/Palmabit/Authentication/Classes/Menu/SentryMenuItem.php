<?php  namespace Palmabit\Authentication\Classes\Menu; 
/**
 * Class MenuItem
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
use Palmabit\Authentication\Interfaces\MenuInterface;

class SentryMenuItem implements MenuInterface
{
    /**
     * @var String
     */
    protected $link;
    /**
     * @var String
     */
    protected $name;
    /**
     * The permission needed to see the menu
     * @var String
     */
    protected $permission;

    function __construct($link, $name, $permission)
    {
        $this->link = $link;
        $this->name = $name;
        $this->permission = $permission;
    }

    /**
     * Check if the current user have access to the menu item
     *
     * @return boolean
     */
    public function havePermission()
    {
        //@todo use sentry check
        return true;
    }

    /**
     * Obtain the menu link
     *
     * @return String
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Obtain the menu name
     *
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Obtain the permission to see the menu
     * @return String
     */
    public function getPermission()
    {
        return $this->permission;
    }

}