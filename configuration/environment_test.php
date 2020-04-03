<?php

return [
    'app_name' => 'Website',
    'url_options' => [
        'host' => 'localhost',
    ],
    'no_syslog' => !getenv('APP_SYSLOG_ENABLED'),
];
