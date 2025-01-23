<?php

namespace Website\migrations;

class Migration202305190001ChangePreferredFieldsOfAccounts
{
    public function migrate(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            PRAGMA foreign_keys = OFF;
            PRAGMA legacy_alter_table = ON;

            ALTER TABLE accounts RENAME TO accounts_old;

            CREATE TABLE accounts (
                id TEXT PRIMARY KEY,
                created_at TEXT NOT NULL,
                expired_at TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                access_token TEXT,
                last_sync_at TEXT,

                preferred_service TEXT NOT NULL DEFAULT 'flusio',
                preferred_tariff TEXT NOT NULL DEFAULT 'stability',
                reminder BOOLEAN NOT NULL DEFAULT false,

                address_first_name TEXT,
                address_last_name TEXT,
                address_address1 TEXT,
                address_postcode TEXT,
                address_city TEXT,
                address_country TEXT,
                company_vat_number TEXT,

                FOREIGN KEY (access_token) REFERENCES tokens(token) ON UPDATE CASCADE ON DELETE SET NULL
            );

            INSERT INTO accounts (
                id,
                created_at,
                expired_at,
                email,
                access_token,
                last_sync_at,

                preferred_service,
                reminder,

                address_first_name,
                address_last_name,
                address_address1,
                address_postcode,
                address_city,
                address_country,
                company_vat_number
            ) SELECT
                id,
                created_at,
                expired_at,
                email,
                access_token,
                last_sync_at,

                preferred_service,
                reminder,

                address_first_name,
                address_last_name,
                address_address1,
                address_postcode,
                address_city,
                address_country,
                company_vat_number
            FROM accounts_old;

            DROP TABLE accounts_old;

            PRAGMA legacy_alter_table = OFF;
            PRAGMA foreign_keys = ON;
        SQL);

        return true;
    }

    public function rollback(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            PRAGMA foreign_keys = OFF;
            PRAGMA legacy_alter_table = ON;

            ALTER TABLE accounts RENAME TO accounts_old;

            CREATE TABLE accounts (
                id TEXT PRIMARY KEY,
                created_at TEXT NOT NULL,
                expired_at TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                access_token TEXT,
                last_sync_at TEXT,

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
                company_vat_number TEXT,

                FOREIGN KEY (access_token) REFERENCES tokens(token) ON UPDATE CASCADE ON DELETE SET NULL
            );

            INSERT INTO accounts (
                id,
                created_at,
                expired_at,
                email,
                access_token,
                last_sync_at,

                preferred_service,
                reminder,

                address_first_name,
                address_last_name,
                address_address1,
                address_postcode,
                address_city,
                address_country,
                company_vat_number
            ) SELECT
                id,
                created_at,
                expired_at,
                email,
                access_token,
                last_sync_at,

                preferred_service,
                reminder,

                address_first_name,
                address_last_name,
                address_address1,
                address_postcode,
                address_city,
                address_country,
                company_vat_number
            FROM accounts_old;

            DROP TABLE accounts_old;

            PRAGMA legacy_alter_table = OFF;
            PRAGMA foreign_keys = ON;
        SQL);

        return true;
    }
}
