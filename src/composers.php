<?php


/**
 * Send to the view the site name
 */
View::composer('*', function ($view) {
    $view->with('app_name', Config::get('authentication::app_name'));
    $view->with('panel_name', Config::get('authentication::panel_name'));
    $view->with('copy_name', Config::get('authentication::copy_name'));
    $view->with('copy_year', Config::get('authentication::copy_year'));
    $view->with('copy_website_url', Config::get('authentication::copy_website_url'));
});

use Palmabit\Authentication\Classes\Filters\GroupsFilter;
use Palmabit\Authentication\Classes\Menu\SentryMenuFactory;

/**
 * Send the menu items
 */
View::composer('authentication::layouts.*', function ($view) {
    $menu_items = SentryMenuFactory::create()->getItemListAvailable();
    $view->with('menu_items', $menu_items);
});

View::composer('authentication::layouts.partials.select_lang', function ($view) {
    $languages = L::getList();
    $view->with('languages', $languages);
});

/**
 * Users sidebar
 */
View::composer(['authentication::user.*'], function ($view) {
    $view->with('sidebar_items', [
        "Lists" => array(URL::route('users.list'), "<i class='glyphicon glyphicon-th-list'></i>"),
        "Add new " => array(URL::route('users.edit'), "<i class='glyphicon glyphicon-plus'></i>"),
    ]);
});

/**
 * Users sidebar
 */
View::composer(['authentication::group.*'], function ($view) {
    $view->with('sidebar_items', [
        "Lists" => array(URL::route('groups.list'), "<i class='glyphicon glyphicon-th-list'></i>"),
        "Add new" => array(URL::route('groups.edit'), "<i class='glyphicon glyphicon-plus'></i>"),
    ]);
});

/**
 * Users sidebar
 */
View::composer(['authentication::permission.*'], function ($view) {
    $view->with('sidebar_items', [
        "Lists" => array(URL::route('permission.list'), "<i class='glyphicon glyphicon-th-list'></i>"),
        "Add new" => array(URL::route('permission.edit'), "<i class='glyphicon glyphicon-plus'></i>"),
    ]);
});

use Palmabit\Authentication\Helpers\FormHelper;
use Palmabit\Authentication\Presenters\GroupPresenter;

/**
 * Sends the permission select to the view
 */
View::composer(['authentication::user.edit', 'authentication::group.edit'], function ($view) {
    $fh = new FormHelper();
    $values_permission = $fh->getSelectValuesPermission();
    $view->with('permission_values', $values_permission);
});
/**
 * Sends the group select to the view
 */
View::composer(['authentication::user.edit', 'authentication::group.edit'], function ($view) {
    $fh = new FormHelper();
    $sentry = \App::make('sentry');
    $groupFilter = new GroupsFilter();
    $values_group = $fh->getSelectValuesGroups();
    $groupsAssignable = $groupFilter->getAssignableGroups($sentry->getUser(),$values_group);
    $view->with('group_values', $groupsAssignable);
});

View::composer('*', function ($view) {

    $perm = Config::get('authentication::permissions.admin_area');
    $auth_helper = App::make('authentication_helper');
    if (!($auth_helper->hasPermission($perm))) {
        $admin_area = false;
    } else {
        $admin_area = true;
    }
    $view->with('admin_area', $admin_area);
});
