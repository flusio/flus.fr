<?php

spl_autoload_register(function ($class_name) {
    $app_name = 'Website';
    $app_path = __DIR__;
    $lib_path = $app_path . '/lib';
    $tests_namespace = 'tests';

    if (strpos($class_name, 'Minz') === 0) {
        include($lib_path . '/Minz/autoload.php');
    } elseif (strpos($class_name, $app_name) === 0) {
        $class_name = substr($class_name, strlen($app_name) + 1);
        $class_path = str_replace('\\', '/', $class_name) . '.php';
        include($app_path . '/src/' . $class_path);
    } elseif (strpos($class_name, 'Stripe') === 0) {
        include($lib_path . '/stripe-php/init.php');
    } elseif ($class_name === 'FPDF') {
        include($lib_path . '/fpdf/fpdf.php');
    } elseif (strpos($class_name, $tests_namespace) === 0) {
        $class_name = substr($class_name, strlen($tests_namespace) + 1);
        $class_path = str_replace('\\', '/', $class_name) . '.php';
        include($app_path . '/tests/' . $class_path);
    }
});
