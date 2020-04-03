<?php

spl_autoload_register(function ($class_name) {
    $app_name = 'Website';

    if (strpos($class_name, 'Minz') === 0) {
        $minz_autoload_path = __DIR__ . '/lib/Minz/autoload.php';
        include($minz_autoload_path);
    } elseif (strpos($class_name, $app_name) === 0) {
        $class_name = substr($class_name, strlen($app_name) + 1);
        $class_path = str_replace('\\', '/', $class_name) . '.php';
        include(__DIR__ . '/src/' . $class_path);
    }
});
