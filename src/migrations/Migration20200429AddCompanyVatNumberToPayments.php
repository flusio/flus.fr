<?php

namespace Website\migrations;

class Migration20200429AddCompanyVatNumberToPayments
{
    public function migrate(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            ALTER TABLE payments
            ADD COLUMN company_vat_number TEXT;
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
                address_country TEXT NOT NULL DEFAULT "FR",
                payment_intent_id TEXT,
                session_id TEXT,
                username TEXT,
                frequency TEXT
            );

            INSERT INTO payments (
                id,
                created_at,
                type,
                invoice_number,
                completed_at,
                email,
                amount,
                address_first_name,
                address_last_name,
                address_address1,
                address_postcode,
                address_city,
                address_country,
                payment_intent_id,
                session_id,
                username,
                frequency
            ) SELECT
                id,
                created_at,
                type,
                invoice_number,
                completed_at,
                email,
                amount,
                address_first_name,
                address_last_name,
                address_address1,
                address_postcode,
                address_city,
                address_country,
                payment_intent_id,
                session_id,
                username,
                frequency
            FROM payments_old;

            DROP TABLE payments_old;
            COMMIT;
        SQL);

        return true;
    }
}
