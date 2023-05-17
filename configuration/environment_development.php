<?php

return [
    'app_name' => 'Website',

    'secret_key' => $dotenv->pop('APP_SECRET_KEY'),

    'url_options' => [
        'host' => 'localhost',
        'port' => 8000,
    ],

    'application' => [
        'admin_secret' => $dotenv->pop('APP_ADMIN_SECRET'),

        'stripe_private_key' => $dotenv->pop('APP_STRIPE_PRIVATE_KEY'),
        'stripe_public_key' => $dotenv->pop('APP_STRIPE_PUBLIC_KEY'),
        'stripe_webhook_secret' => $dotenv->pop('APP_STRIPE_WEBHOOK_SECRET'),

        'flus_private_key' => $dotenv->pop('APP_FLUS_PRIVATE_KEY'),

        'support_email' => $dotenv->pop('APP_SUPPORT_EMAIL'),

        'financial_goal' => intval($dotenv->pop('APP_FINANCIAL_GOAL', '36000')),
    ],

    'database' => [
        'dsn' => "sqlite:{$app_path}/data/db.sqlite",
    ],

    'mailer' => [
        'type' => $dotenv->pop('APP_MAILER'),
        'from' => $dotenv->pop('APP_SMTP_FROM'),
        'smtp' => [
            'domain' => $dotenv->pop('APP_SMTP_DOMAIN'),
            'host' => $dotenv->pop('APP_SMTP_HOST'),
            'port' => intval($dotenv->pop('APP_SMTP_PORT')),
            'auth' => (bool)$dotenv->pop('APP_SMTP_AUTH'),
            'auth_type' => $dotenv->pop('APP_SMTP_AUTH_TYPE'),
            'username' => $dotenv->pop('APP_SMTP_USERNAME'),
            'password' => $dotenv->pop('APP_SMTP_PASSWORD'),
            'secure' => $dotenv->pop('APP_SMTP_SECURE'),
        ],
    ],
];
