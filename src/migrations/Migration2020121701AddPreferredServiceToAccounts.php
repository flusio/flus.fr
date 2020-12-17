<?php

namespace Website\migrations;

class Migration2020121701AddPreferredServiceToAccounts
{
    public function migrate()
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            ALTER TABLE accounts
            ADD COLUMN preferred_service TEXT NOT NULL DEFAULT 'flusio';
        SQL);

        return true;
    }

    public function rollback()
    {
        // Removing a column in accounts with dependent payments is too
        // complicated.
        return true;
    }
}
