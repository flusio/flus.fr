<?php

return [
    'app_name' => 'Website',

    'url_options' => [
        'host' => 'localhost',
        'port' => 8000,
    ],

    'application' => [
        'enabled' => true,

        'admin_secret' => getenv('APP_ADMIN_SECRET'),

        'stripe_private_key' => getenv('APP_STRIPE_PRIVATE_KEY'),
        'stripe_public_key' => getenv('APP_STRIPE_PUBLIC_KEY'),
        'stripe_webhook_secret' => getenv('APP_STRIPE_WEBHOOK_SECRET'),

        'flus_private_key' => getenv('FLUS_PRIVATE_KEY'),
    ],

    'database' => [
        'dsn' => "sqlite:{$app_path}/data/db.sqlite",
    ],

    'mailer' => [
        'type' => getenv('APP_MAILER'),
        'from' => getenv('APP_SMTP_FROM'),
        'smtp' => [
            'domain' => getenv('APP_SMTP_DOMAIN'),
            'host' => getenv('APP_SMTP_HOST'),
            'port' => intval(getenv('APP_SMTP_PORT')),
            'auth' => (bool)getenv('APP_SMTP_AUTH'),
            'auth_type' => getenv('APP_SMTP_AUTH_TYPE'),
            'username' => getenv('APP_SMTP_USERNAME'),
            'password' => getenv('APP_SMTP_PASSWORD'),
            'secure' => getenv('APP_SMTP_SECURE'),
        ],
    ],
];
