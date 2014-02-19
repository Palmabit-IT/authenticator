<?php

/**
 * Send to the view the site name
 */
View::composer('authentication::*', function ($view){
    $view->with('app_name', Config::get('authentication::app_name') );
});

use Palmabit\Authentication\Classes\Menu\SentryMenuFactory;
/**
 * Send the menu items
 */
View::composer('authentication::layouts.*', function ($view){
    $menu_items = SentryMenuFactory::create()->getItemListAvailable();
    $view->with('menu_items', $menu_items);
});

/**
 * Create users sidebar
 */
View::composer(['authentication::user.*'], function ($view){
    $view->with('sidebar_items', [
                                    "Lista utenti" => URL::route('users.list'),
                                    "Aggiungi utente" => URL::route('users.edit')
                                 ]);
});