<?php

return [
    'app_name' => 'Website',
    'url_options' => [
        'host' => 'localhost',
        'port' => 8000,
    ],
    'application' => [
        'stripe_private_key' => getenv('APP_STRIPE_PRIVATE_KEY'),
        'stripe_public_key' => getenv('APP_STRIPE_PUBLIC_KEY'),
    ],
];
