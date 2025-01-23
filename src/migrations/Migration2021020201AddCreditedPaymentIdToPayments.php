<?php

namespace Website\migrations;

class Migration2021020201AddCreditedPaymentIdToPayments
{
    public function migrate(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            ALTER TABLE payments
            ADD COLUMN credited_payment_id TEXT
            REFERENCES payments(id) ON UPDATE CASCADE ON DELETE SET NULL;
        SQL);

        return true;
    }

    public function rollback(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            ALTER TABLE payments RENAME TO payments_old;

            CREATE TABLE payments (
                id TEXT PRIMARY KEY NOT NULL,
                created_at TEXT NOT NULL,
                completed_at TEXT,
                is_paid BOOLEAN NOT NULL DEFAULT false,
                type TEXT NOT NULL,

                invoice_number TEXT,
                email TEXT NOT NULL,
                amount INTEGER NOT NULL,
                frequency TEXT,
                company_vat_number TEXT,

                address_first_name TEXT NOT NULL,
                address_last_name TEXT NOT NULL,
                address_address1 TEXT NOT NULL,
                address_postcode TEXT NOT NULL,
                address_city TEXT NOT NULL,
                address_country TEXT NOT NULL DEFAULT "FR",

                payment_intent_id TEXT,
                session_id TEXT,

                account_id TEXT,

                FOREIGN KEY (account_id) REFERENCES accounts(id) ON UPDATE CASCADE ON DELETE SET NULL
            );

            INSERT INTO payments (
                id,
                created_at,
                completed_at,
                is_paid,
                type,

                invoice_number,
                email,
                amount,
                frequency,
                company_vat_number,

                address_first_name,
                address_last_name,
                address_address1,
                address_postcode,
                address_city,
                address_country,

                payment_intent_id,
                session_id,

                account_id
            ) SELECT
                id,
                created_at,
                completed_at,
                is_paid,
                type,

                invoice_number,
                email,
                amount,
                frequency,
                company_vat_number,

                address_first_name,
                address_last_name,
                address_address1,
                address_postcode,
                address_city,
                address_country,

                payment_intent_id,
                session_id,

                account_id
            FROM payments_old;

            DROP TABLE payments_old;
        SQL);

        return true;
    }
}
