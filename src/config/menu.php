<?php

return [
        "list" => [
                [
                        "name"        => "Permission",
                        "route"       => "permission",
                        "link"        => URL::route('permission.list'),
                        "permissions" => ["_superadmin"]
                ],
                [
                        "name"        => "Groups",
                        "route"       => "groups",
                        "link"        => URL::route('groups.list'),
                        "permissions" => ["_superadmin"]
                ],
                [
                  /*
                   * the name of the link: you will see it in the admin menu panel.
                   * Note: If you don't want to show this item in the menu
                   * but still want to handle permission with the 'can_see' filter
                   * just leave this field empty.
                   */
                  "name"        => "Users",
                  /* the route name associated to the link: used to set
                   * the 'active' flag and to validate permissions of all
                   * the subroutes associated(users.* will be validated for _superadmin and _group-editor permission)
                   */
                  "route"       => "users",
                  /*
                   * the acual link associated to the menu item
                   */
                  "link"        => URL::route('users.list'),
                  /* the list of 'permission name' associated to the menu
                  * item: if the logged use has one or more of the permission
                  * in the list he can see the menu link and access the area
                  * associated with that.
                  * Every route that you create with the 'route' as a prefix
                  * will check for the permissions and throw a 401 error if the
                  * check fails (for example in this case every route named users.*)
                  */
                  "permissions" => ["_admin", "_superadmin"]
                ]
        ]
];