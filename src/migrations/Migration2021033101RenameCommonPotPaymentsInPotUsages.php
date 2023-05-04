<?php

namespace Website\migrations;

class Migration2021033101RenameCommonPotPaymentsInPotUsages
{
    public function migrate(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            ALTER TABLE common_pot_payments
            RENAME TO pot_usages;
        SQL);

        return true;
    }

    public function rollback(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            ALTER TABLE pot_usages
            RENAME TO common_pot_payments;
        SQL);

        return true;
    }
}
