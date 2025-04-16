<?php

return [
    'app_name' => 'Website',

    'secret_key' => $dotenv->pop('APP_SECRET_KEY'),

    'url_options' => [
        'protocol' => 'https',
        'host' => $dotenv->pop('APP_HOST'),
    ],

    'application' => [
        'admin_secret' => $dotenv->pop('APP_ADMIN_SECRET'),

        'stripe_private_key' => $dotenv->pop('APP_STRIPE_PRIVATE_KEY'),
        'stripe_public_key' => $dotenv->pop('APP_STRIPE_PUBLIC_KEY'),
        'stripe_webhook_secret' => $dotenv->pop('APP_STRIPE_WEBHOOK_SECRET'),

        'flus_private_key' => $dotenv->pop('APP_FLUS_PRIVATE_KEY'),

        'support_email' => $dotenv->pop('APP_SUPPORT_EMAIL'),

        'financial_goal' => intval($dotenv->pop('APP_FINANCIAL_GOAL', '36000')),

        'plausible_url' => $dotenv->pop('PLAUSIBLE_URL'),

        'bileto_url' => $dotenv->pop('BILETO_URL'),
        'bileto_api_token' => $dotenv->pop('BILETO_API_TOKEN'),
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
            'auth' => filter_var($dotenv->pop('APP_SMTP_AUTH', 'false'), FILTER_VALIDATE_BOOLEAN),
            'auth_type' => $dotenv->pop('APP_SMTP_AUTH_TYPE', ''),
            'username' => $dotenv->pop('APP_SMTP_USERNAME'),
            'password' => $dotenv->pop('APP_SMTP_PASSWORD'),
            'secure' => $dotenv->pop('APP_SMTP_SECURE', ''),
        ],
    ],
];
