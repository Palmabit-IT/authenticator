<?php

return [
        [
            "name" => "Utenti",
            "route" => "users",
            "link" => URL::route('users.list'),
            "permissions" => ["_admin"]
        ],
];