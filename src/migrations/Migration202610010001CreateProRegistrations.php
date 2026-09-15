<?php

namespace Website\migrations;

class Migration202610010001CreateProRegistrations
{
    public function migrate(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            CREATE TABLE pro_registrations (
                id TEXT PRIMARY KEY NOT NULL,
                created_at TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                organisation TEXT NOT NULL DEFAULT ''
            );
        SQL);

        return true;
    }

    public function rollback(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            DROP TABLE pro_registrations;
        SQL);

        return true;
    }
}
