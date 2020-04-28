<?php

namespace Website\migration_2020_04_28_AddAddressCountryToPayments;

function migrate()
{
    $database = \Minz\Database::get();
    $column = 'address_country TEXT NOT NULL DEFAULT "FR"';
    $sql = "ALTER TABLE payments ADD COLUMN {$column}";
    $result = $database->exec($sql);

    if ($result === false) {
        $error_info = $database->errorInfo();
        throw new \Minz\Errors\DatabaseModelError(
            "Error in SQL statement: {$error_info[2]} ({$error_info[0]})."
        );
    }

    return true;
}
