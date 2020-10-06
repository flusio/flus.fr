<?php

namespace Website\migrations;

class Migration2020100601CreateTokens
{
    public function migrate()
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            CREATE TABLE tokens (
                token TEXT PRIMARY KEY,
                created_at TEXT NOT NULL,
                expired_at TEXT NOT NULL,
                invalidated_at TEXT
            );
        SQL);

        return true;
    }

    public function rollback()
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            DROP TABLE tokens;
        SQL);

        return true;
    }
}
