<?php

namespace Website\migrations;

class Migration202404190001RenameFlusioServiceToFlus
{
    public function migrate(): bool
    {
        $database = \Minz\Database::get();

        $database->beginTransaction();

        $database->exec(<<<'SQL'
            UPDATE accounts
            SET preferred_service = 'flus'
            WHERE preferred_service = 'flusio';

            ALTER TABLE accounts
            RENAME COLUMN preferred_service
            TO old_preferred_service;

            ALTER TABLE accounts
            ADD COLUMN preferred_service TEXT NOT NULL DEFAULT 'flus';

            UPDATE accounts
            SET preferred_service = old_preferred_service;

            ALTER TABLE accounts
            DROP COLUMN old_preferred_service;
        SQL);

        $database->commit();

        return true;
    }

    public function rollback(): bool
    {
        $database = \Minz\Database::get();

        $database->beginTransaction();

        $database->exec(<<<'SQL'
            UPDATE accounts
            SET preferred_service = 'flusio'
            WHERE preferred_service = 'flus';

            ALTER TABLE accounts
            RENAME COLUMN preferred_service
            TO old_preferred_service;

            ALTER TABLE accounts
            ADD COLUMN preferred_service TEXT NOT NULL DEFAULT 'flusio';

            UPDATE accounts
            SET preferred_service = old_preferred_service;

            ALTER TABLE accounts
            DROP COLUMN old_preferred_service;
        SQL);

        $database->commit();

        return true;
    }
}
