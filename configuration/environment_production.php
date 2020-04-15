<?php

return [
    'app_name' => 'Website',
    'use_session' => false,
    'url_options' => [
        'protocol' => 'https',
        'host' => getenv('APP_HOST'),
    ],
    'application' => [
        'stripe_private_key' => getenv('APP_STRIPE_PRIVATE_KEY'),
        'stripe_public_key' => getenv('APP_STRIPE_PUBLIC_KEY'),
        'stripe_webhook_secret' => getenv('APP_STRIPE_WEBHOOK_SECRET'),

        'flus_private_key' => getenv('FLUS_PRIVATE_KEY'),
    ],
    'database' => [
        'dsn' => "sqlite:{$app_path}/data/db.sqlite",
    ],
];
