<?php

return [
    // TODO : AFFICHER LE NOMBRE DE TICKET OUVERT
    trans("support_alfiory::admin.support") => [
        "type" => "dropdown",
        "icon" => "fas fa-life-ring",
        "permissions" => "DASHBOARD_SUPPORT_VIEW_CATEGORIES|admin",
        "lists" => [
            trans("support_alfiory::admin.categories") => [
                "type" => "simple",
                "open_blank" => false,
                "url" => route('admin.support_alfiory.categories'),
                "permissions" => "DASHBOARD_SUPPORT_VIEW_CATEGORIES|admin",
            ],
            trans("support_alfiory::admin.tickets") => [
                "type" => "simple",
                "open_blank" => false,
                "url" => route('admin.support_alfiory.tickets'),
            ],
        ]
    ],
];

