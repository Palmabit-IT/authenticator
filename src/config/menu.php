<?php

return [
        [
            /**
             * Name of the menu field in the panel
             */
            "name" => "Utenti",
            /**
             * Name of the route associated with the button
             */
            "route" => "users",
            /**
             * Effective link url
             */
            "link" => URL::route('users.list'),
            /**
             * Name of the permission needed to view the button
             */
            "permissions" => ["_admin"]
        ],
];