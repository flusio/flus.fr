<?php

namespace Website\migrations;

class Migration202306140001AddQuantityToPayments
{
    public function migrate(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            ALTER TABLE payments ADD COLUMN quantity INTEGER NOT NULL DEFAULT 1;
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

            CREATE TABLE payments_tmp (
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

            INSERT INTO payments_tmp (
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
            ) SELECT
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

            ALTER TABLE payments_tmp RENAME TO payments;

            COMMIT;

            PRAGMA legacy_alter_table = OFF;
            PRAGMA foreign_keys = ON;
        SQL);

        return true;
    }
}
