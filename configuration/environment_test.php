<?php

$number_of_datasets = getenv('NUMBER_OF_DATASETS');
if ($number_of_datasets < 1) {
    $number_of_datasets = 1;
}

return [
    'app_name' => 'Website',
    'url_options' => [
        'host' => 'localhost',
    ],
    'application' => [
        'stripe_private_key' => 'sk_test_123',
        'stripe_public_key' => 'pk_test_123',

        'flus_private_key' => 'sk_test_123',

        // used only in tests
        'number_of_datasets' => $number_of_datasets,
    ],
    'database' => [
        'dsn' => 'sqlite::memory:',
    ],
    'no_syslog' => !getenv('APP_SYSLOG_ENABLED'),
];
