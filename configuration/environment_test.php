<?php

return [
    'app_name' => 'Website',
    'url_options' => [
        'host' => 'localhost',
    ],
    'application' => [
        'stripe_private_key' => 'sk_test_123',
        'stripe_public_key' => 'pk_test_123',
    ],
    'no_syslog' => !getenv('APP_SYSLOG_ENABLED'),
];
