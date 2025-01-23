<?php

namespace Website\migrations;

class Migration2021040101ReorganizePayments
{
    public function migrate(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            PRAGMA foreign_keys = OFF;

            ALTER TABLE accounts ADD COLUMN company_vat_number TEXT;

            UPDATE accounts
            SET address_first_name = (SELECT address_first_name FROM payments WHERE account_id = accounts.id),
                address_last_name = (SELECT address_last_name FROM payments WHERE account_id = accounts.id),
                address_address1 = (SELECT address_address1 FROM payments WHERE account_id = accounts.id),
                address_postcode = (SELECT address_postcode FROM payments WHERE account_id = accounts.id),
                address_city = (SELECT address_city FROM payments WHERE account_id = accounts.id),
                address_country = (SELECT address_country FROM payments WHERE account_id = accounts.id)
            WHERE accounts.address_first_name is NULL;

            UPDATE accounts
            SET company_vat_number = (SELECT company_vat_number FROM payments WHERE account_id = accounts.id)
            WHERE accounts.company_vat_number is NULL;

            CREATE TABLE payments_new (
                id TEXT PRIMARY KEY NOT NULL,
                created_at TEXT NOT NULL,
                completed_at TEXT,
                is_paid BOOLEAN NOT NULL DEFAULT false,
                type TEXT NOT NULL,

                invoice_number TEXT,
                amount INTEGER NOT NULL,
                frequency TEXT,
                credited_payment_id TEXT,

                payment_intent_id TEXT,
                session_id TEXT,

                account_id TEXT NOT NULL,

                FOREIGN KEY (credited_payment_id) REFERENCES payments(id) ON UPDATE CASCADE ON DELETE RESTRICT,
                FOREIGN KEY (account_id) REFERENCES accounts(id) ON UPDATE CASCADE ON DELETE RESTRICT
            );

            INSERT INTO payments_new
            SELECT
                id,
                created_at,
                completed_at,
                is_paid,
                type,
                invoice_number,
                amount,
                frequency,
                credited_payment_id,
                payment_intent_id,
                session_id,
                account_id
            FROM payments;

            DROP TABLE payments;

            ALTER TABLE payments_new RENAME TO payments;

            PRAGMA foreign_keys = ON;
        SQL);

        return true;
    }

    public function rollback(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            PRAGMA foreign_keys = OFF;

            CREATE TABLE payments_new (
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
                credited_payment_id TEXT,

                address_first_name TEXT NOT NULL,
                address_last_name TEXT NOT NULL,
                address_address1 TEXT NOT NULL,
                address_postcode TEXT NOT NULL,
                address_city TEXT NOT NULL,
                address_country TEXT NOT NULL DEFAULT "FR",

                payment_intent_id TEXT,
                session_id TEXT,

                account_id TEXT,

                FOREIGN KEY (credited_payment_id) REFERENCES payments(id) ON UPDATE CASCADE ON DELETE SET NULL,
                FOREIGN KEY (account_id) REFERENCES accounts(id) ON UPDATE CASCADE ON DELETE SET NULL
            );

            INSERT INTO payments_new (
                id,
                created_at,
                completed_at,
                is_paid,
                type,
                invoice_number,
                amount,
                frequency,
                credited_payment_id,
                payment_intent_id,
                session_id,
                account_id,
                company_vat_number,
                email,
                address_first_name,
                address_last_name,
                address_address1,
                address_postcode,
                address_city,
                address_country
            )
            SELECT
                p.id,
                p.created_at,
                p.completed_at,
                p.is_paid,
                p.type,
                p.invoice_number,
                p.amount,
                p.frequency,
                p.credited_payment_id,
                p.payment_intent_id,
                p.session_id,
                p.account_id,
                a.company_vat_number,
                a.email,
                a.address_first_name,
                a.address_last_name,
                a.address_address1,
                a.address_postcode,
                a.address_city,
                a.address_country
            FROM payments p, accounts a
            WHERE p.account_id = a.id;

            DROP TABLE payments;

            ALTER TABLE payments_new RENAME TO payments;

            CREATE TABLE accounts_new (
                id TEXT PRIMARY KEY,
                created_at TEXT NOT NULL,
                expired_at TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                access_token TEXT,

                preferred_frequency TEXT NOT NULL DEFAULT 'month',
                preferred_payment_type TEXT NOT NULL DEFAULT 'card',
                preferred_service TEXT NOT NULL DEFAULT 'flusio',
                reminder BOOLEAN NOT NULL DEFAULT false,

                address_first_name TEXT,
                address_last_name TEXT,
                address_address1 TEXT,
                address_postcode TEXT,
                address_city TEXT,
                address_country TEXT,

                FOREIGN KEY (access_token) REFERENCES tokens(token) ON UPDATE CASCADE ON DELETE SET NULL
            );

            INSERT INTO accounts_new
            SELECT
                id,
                created_at,
                expired_at,
                email,
                access_token,
                preferred_frequency,
                preferred_payment_type,
                preferred_service,
                reminder,
                address_first_name,
                address_last_name,
                address_address1,
                address_postcode,
                address_city,
                address_country
            FROM accounts;

            DROP TABLE accounts;

            ALTER TABLE accounts_new RENAME TO accounts;

            PRAGMA foreign_keys = ON;
        SQL);

        return true;
    }
}
