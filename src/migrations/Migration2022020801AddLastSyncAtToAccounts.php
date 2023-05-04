<?php

namespace Website\migrations;

class Migration2022020801AddLastSyncAtToAccounts
{
    public function migrate(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            ALTER TABLE accounts
            ADD COLUMN last_sync_at TEXT;
        SQL);

        return true;
    }

    public function rollback(): bool
    {
        // Removing a column in accounts with dependent payments is too
        // complicated.
        return true;
    }
}
