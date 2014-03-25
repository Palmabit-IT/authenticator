<?php

return [
        [
            "name" => "Utenti",
            "route" => "users",
            "link" => URL::route('users.list'),
            "permissions" => []
        ],
        [
            "name" => "Faq",
            "route" => "faq",
            "link" => URL::route('faq.lists'),
            "permissions" => []
        ],
        [
            "name" => "Categorie",
            "route" => "category",
            "link" => URL::route('category.lists'),
            "permissions" => []
        ]
];