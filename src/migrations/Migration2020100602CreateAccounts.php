<?php

namespace Website\migrations;

class Migration2020100602CreateAccounts
{
    public function migrate()
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            CREATE TABLE accounts (
                id TEXT PRIMARY KEY,
                created_at TEXT NOT NULL,
                expired_at TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                access_token TEXT,

                preferred_frequency TEXT,
                preferred_payment_type TEXT,
                reminder BOOLEAN NOT NULL DEFAULT false,

                address_first_name TEXT,
                address_last_name TEXT,
                address_address1 TEXT,
                address_postcode TEXT,
                address_city TEXT,
                address_country TEXT,

                FOREIGN KEY (access_token) REFERENCES tokens(token) ON UPDATE CASCADE ON DELETE SET NULL
            );
        SQL);

        return true;
    }

    public function rollback()
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            DROP TABLE accounts;
        SQL);

        return true;
    }
}
