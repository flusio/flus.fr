<?php

namespace Website\migrations;

class Migration20200000CreatePayments
{
    public function migrate()
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            CREATE TABLE payments (
                id TEXT PRIMARY KEY NOT NULL,
                created_at DATETIME NOT NULL,
                type TEXT NOT NULL,
                invoice_number TEXT,
                completed_at DATETIME,
                email TEXT NOT NULL,
                amount INTEGER NOT NULL,
                address_first_name TEXT NOT NULL,
                address_last_name TEXT NOT NULL,
                address_address1 TEXT NOT NULL,
                address_postcode TEXT NOT NULL,
                address_city TEXT NOT NULL,
                payment_intent_id TEXT,
                session_id TEXT,
                username TEXT,
                frequency TEXT
            );
        SQL);

        return true;
    }

    public function rollback()
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            DROP TABLE payments;
        SQL);

        return true;
    }
}
