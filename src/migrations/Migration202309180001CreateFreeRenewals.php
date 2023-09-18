<?php

namespace Website\migrations;

class Migration202309180001CreateFreeRenewals
{
    public function migrate(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            CREATE TABLE free_renewals (
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                created_at TEXT NOT NULL,
                quantity INTEGER NOT NULL DEFAULT 1
            );
        SQL);

        return true;
    }

    public function rollback(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            DROP TABLE free_renewals;
        SQL);

        return true;
    }
}
