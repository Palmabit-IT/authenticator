<?php

return [
        [
                "name"        => "Users",
                "route"       => "users",
                "link"        => URL::route('users.list'),
                "permissions" => ["_admin", "_superadmin"]
        ],
        [
                "name"        => "Groups",
                "route"       => "groups",
                "link"        => URL::route('groups.list'),
                "permissions" => ["_superadmin"]
        ],
        [
                "name"        => "Permission",
                "route"       => "permission",
                "link"        => URL::route('permission.list'),
                "permissions" => ["_superadmin"]
        ],
        //        [
        //            "name" => "Products",
        //            "route" => "products",
        //            "link" => URL::route('products.lists'),
        //            "permissions" => ["_admin"]
        //        ],
        //        [
        //            "name" => "Categories",
        //            "route" => "category",
        //            "link" => URL::route('category.lists'),
        //            "permissions" => ["_admin"]
        //        ]
];