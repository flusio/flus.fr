<?php

$number_of_datasets = getenv('NUMBER_OF_DATASETS');
if ($number_of_datasets < 1) {
    $number_of_datasets = 1;
}

$temporary_directory = sys_get_temp_dir() . '/flus';
@mkdir($temporary_directory);

return [
    'app_name' => 'Website',

    'secret_key' => 'change-me',

    'url_options' => [
        'host' => 'localhost',
    ],

    'application' => [
        'admin_secret' => \password_hash('secret', \PASSWORD_BCRYPT),

        'stripe_private_key' => 'sk_test_123',
        'stripe_public_key' => 'pk_test_123',

        'flus_private_key' => 'sk_test_123',

        'support_email' => 'support@example.com',

        // used only in tests
        'number_of_datasets' => $number_of_datasets,
    ],

    'database' => [
        'dsn' => 'sqlite::memory:',
    ],

    'mailer' => [
        'type' => 'test',
        'from' => 'root@localhost',
    ],

    'data_path' => $temporary_directory,
    'no_syslog_output' => true,
];
