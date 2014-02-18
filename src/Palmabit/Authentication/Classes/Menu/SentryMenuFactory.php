<?php  namespace Palmabit\Authentication\Classes\Menu; 
/**
 * Class SentryFactory
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */

use Config;

class SentryMenuFactory
{
    protected static $config_file = "authentication::menu";

    public static function create($config_file = null)
    {
        // load the config file
        $config_file = $config_file ? $config_file : static::$config_file;
        $menu_items = Config::get($config_file);

        $items = [];
        foreach ($menu_items as $menu_item)
        {
            $items[] = new SentryMenuItem($menu_item["link"], $menu_item["name"], $menu_item["permission"]);
        }

        return new MenuItemCollection($items);
    }
} 