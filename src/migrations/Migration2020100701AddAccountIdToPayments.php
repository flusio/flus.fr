<?php

namespace Website\migrations;

class Migration2020100701AddAccountIdToPayments
{
    public function migrate(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            ALTER TABLE payments
            ADD COLUMN account_id TEXT REFERENCES accounts(id) ON UPDATE CASCADE ON DELETE SET NULL;
        SQL);

        return true;
    }

    public function rollback(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            BEGIN TRANSACTION;
            ALTER TABLE payments RENAME TO payments_old;

            CREATE TABLE payments (
                id TEXT PRIMARY KEY NOT NULL,
                created_at TEXT NOT NULL,
                completed_at TEXT,
                type TEXT NOT NULL,

                invoice_number TEXT,
                email TEXT NOT NULL,
                amount INTEGER NOT NULL,
                username TEXT,
                frequency TEXT,
                company_vat_number TEXT,

                address_first_name TEXT NOT NULL,
                address_last_name TEXT NOT NULL,
                address_address1 TEXT NOT NULL,
                address_postcode TEXT NOT NULL,
                address_city TEXT NOT NULL,
                address_country TEXT NOT NULL DEFAULT "FR",

                payment_intent_id TEXT,
                session_id TEXT
            );

            INSERT INTO payments (
                id,
                created_at,
                completed_at,
                type,

                invoice_number,
                email,
                amount,
                username,
                frequency,
                company_vat_number,

                address_first_name,
                address_last_name,
                address_address1,
                address_postcode,
                address_city,
                address_country,

                payment_intent_id,
                session_id
            ) SELECT
                id,
                created_at,
                completed_at,
                type,

                invoice_number,
                email,
                amount,
                username,
                frequency,
                company_vat_number,

                address_first_name,
                address_last_name,
                address_address1,
                address_postcode,
                address_city,
                address_country,

                payment_intent_id,
                session_id
            FROM payments_old;

            DROP TABLE payments_old;
            COMMIT;
        SQL);

        return true;
    }
}
