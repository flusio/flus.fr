<?php

namespace Website\controllers\system;

function init()
{
    $app_path = \Minz\Configuration::$app_path;
    $schema_path = $app_path . '/src/schema.sql';

    if (file_exists($schema_path)) {
        $schema = file_get_contents($schema_path);
        $database = \Minz\Database::get();
        $database->exec($schema);
    }

    $output = new \Minz\Output\Text("The system has been initialized.\n");
    return new \Minz\Response(200, $output);
}
