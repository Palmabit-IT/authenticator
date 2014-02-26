<?php

return [
        [
            "name" => "Utenti",
            "route" => "users",
            "link" => URL::route('users.list'),
            "permissions" => ["_admin"]
        ],
        [
            "name" => "Prodotti",
            "route" => "products",
            "link" => URL::route('products.lists'),
            "permissions" => ["_admin"]
        ],
        [
            "name" => "Categorie",
            "route" => "category",
            "link" => URL::route('category.lists'),
            "permissions" => ["_admin"]
        ]
];