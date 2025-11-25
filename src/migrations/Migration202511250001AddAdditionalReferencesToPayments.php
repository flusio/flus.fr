<?php

namespace Website\migrations;

class Migration202511250001AddAdditionalReferencesToPayments
{
    public function migrate(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            ALTER TABLE payments
            ADD COLUMN additional_references TEXT NOT NULL DEFAULT '';
        SQL);

        return true;
    }

    public function rollback(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            ALTER TABLE payments
            DROP COLUMN additional_references;
        SQL);

        return true;
    }
}
