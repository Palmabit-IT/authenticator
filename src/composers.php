<?php

/**
 * Send to the view the site name
 */
View::composer('*', function ($view){
    $view->with('app_name', Config::get('authentication::app_name') );
    $view->with('panel_name', Config::get('authentication::panel_name') );
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
View::composer(['authentication::user.*', 'authentication::group.*', 'authentication::permission.*'], function ($view){
    $view->with('sidebar_items', [
                                    "Lista utenti" =>  URL::route('users.list'),
                                    "Aggiungi utente" =>  URL::route('users.edit'),
                                    //"Lista gruppi" => array( URL::route('users.groups.list'), "<i class='glyphicon glyphicon-th-list'></i>"),
                                    //"Aggiungi gruppo" => array( URL::route('users.groups.edit'), "<i class='glyphicon glyphicon-plus'></i>"),
                                    //"Lista permessi" => array( URL::route('users.permission.list'), "<i class='glyphicon glyphicon-th-list'></i>"),
                                    //"Aggiungi permesso" => array( URL::route('users.permission.edit'), "<i class='glyphicon glyphicon-plus'></i>"),
                                 ]);
});

use Palmabit\Authentication\Helpers\FormHelper;
/**
 * Sends the permission select to the view
 */
View::composer(['authentication::user.edit','authentication::group.edit'], function ($view){
    $fh = new FormHelper();
    $values_permission = $fh->getSelectValuesPermission();
    $view->with('permission_values', $values_permission);
});
/**
 * Sends the group select to the view
 */
View::composer(['authentication::user.edit','authentication::group.edit'], function ($view){
    $fh = new FormHelper();
    $values_group = $fh->getSelectValuesGroups();
    $view->with('group_values', $values_group);
});


View::composer('*', function ($view) {

    $perm = Config::get('authentication::permissions.admin_area');
    $auth_helper = App::make('authentication_helper');
    if( ! ($auth_helper->hasPermission($perm)) ) {
        $admin_area = false;
    } else {
        $admin_area = true;
    }
    $view->with('admin_area', $admin_area);
});
