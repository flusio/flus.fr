<?php

namespace Website\migrations;

class Migration202306130001AddLegalFieldsToAccounts
{
    public function migrate(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            ALTER TABLE accounts ADD COLUMN entity_type TEXT NOT NULL DEFAULT 'natural';
            ALTER TABLE accounts ADD COLUMN address_legal_name TEXT;
        SQL);

        return true;
    }

    public function rollback(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            PRAGMA foreign_keys = OFF;
            PRAGMA legacy_alter_table = ON;

            BEGIN TRANSACTION;

            CREATE TABLE accounts_tmp (
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

            INSERT INTO accounts_tmp (
                id,
                created_at,
                expired_at,
                email,
                access_token,
                last_sync_at,

                preferred_service,
                preferred_tariff,
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
                preferred_tariff,
                reminder,

                address_first_name,
                address_last_name,
                address_address1,
                address_postcode,
                address_city,
                address_country,
                company_vat_number
            FROM accounts;

            DROP TABLE accounts;

            ALTER TABLE accounts_tmp RENAME TO accounts;

            COMMIT;

            PRAGMA legacy_alter_table = OFF;
            PRAGMA foreign_keys = ON;
        SQL);

        return true;
    }
}
