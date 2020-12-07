<?php

namespace Website\migrations;

class Migration2020120701CreateCommonPotPayments
{
    public function migrate()
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            CREATE TABLE common_pot_payments (
                id TEXT PRIMARY KEY NOT NULL,
                created_at TEXT NOT NULL,
                completed_at TEXT,
                is_paid BOOLEAN NOT NULL DEFAULT true,

                amount INTEGER NOT NULL,
                frequency TEXT,

                account_id TEXT,

                FOREIGN KEY (account_id) REFERENCES accounts(id) ON UPDATE CASCADE ON DELETE SET NULL
            );
        SQL);

        return true;
    }

    public function rollback()
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            DROP TABLE common_pot_payments;
        SQL);

        return true;
    }
}
