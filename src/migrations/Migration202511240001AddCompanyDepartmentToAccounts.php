<?php

namespace Website\migrations;

class Migration202511240001AddCompanyDepartmentToAccounts
{
    public function migrate(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            ALTER TABLE accounts
            ADD COLUMN company_department TEXT;
        SQL);

        return true;
    }

    public function rollback(): bool
    {
        $database = \Minz\Database::get();

        $database->exec(<<<'SQL'
            ALTER TABLE accounts
            DROP COLUMN company_department;
        SQL);

        return true;
    }
}
