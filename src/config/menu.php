<?php

return [
        [
            "name" => "Utenti",
            "route" => "users",
            "link" => URL::route('users.list'),
            "permissions" => [""]
        ],
        [
            "name" => "Prodotti",
            "route" => "products",
            "link" => URL::route('products.lists'),
            "permissions" => [""]
        ],
        [
            "name" => "Categorie",
            "route" => "category",
            "link" => URL::route('category.lists'),
            "permissions" => [""]
        ]
];