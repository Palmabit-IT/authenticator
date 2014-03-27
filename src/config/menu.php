<?php

return [
        [
            "name" => "Utenti",
            "route" => "users",
            "link" => URL::route('users.list'),
            "permissions" => ["_admin"]
        ],
        [
            "name" => "Faq",
            "route" => "faq",
            "link" => URL::route('faq.lists'),
            "permissions" => ["_admin"]
        ],
        [
            "name" => "Categorie",
            "route" => "category",
            "link" => URL::route('category.lists'),
            "permissions" => ["_admin"]
        ]
];