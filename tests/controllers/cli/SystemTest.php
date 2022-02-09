<?php

namespace Website\controllers\cli;

class SystemTest extends \PHPUnit\Framework\TestCase
{
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;

    /**
     * @before
     */
    public function uninstall()
    {
        $migration_file = \Minz\Configuration::$data_path . '/migrations_version.txt';
        @unlink($migration_file);
        \Minz\Database::drop();
    }

    public function testInitSucceedsWhenFirstTime()
    {
        $migration_file = \Minz\Configuration::$data_path . '/migrations_version.txt';

        $this->assertFalse(file_exists($migration_file));

        $response = $this->appRun('cli', '/system/init');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'The system has been initialized.');
        $this->assertTrue(file_exists($migration_file));
    }

    public function testInitFailsWhenCallingTwice()
    {
        $this->appRun('cli', '/system/init');
        $response = $this->appRun('cli', '/system/init');

        $this->assertResponseCode($response, 500);
        $this->assertResponseContains($response, 'The system is already initialized.');
    }

    public function testMigrateSucceeds()
    {
        $migration_file = \Minz\Configuration::$data_path . '/migrations_version.txt';
        touch($migration_file);
        \Minz\Database::create();

        $response = $this->appRun('cli', '/system/migrate');

        $this->assertResponseCode($response, 200);
    }

    public function testMigrateDoesNothingWhenUptodate()
    {
        $migration_file = \Minz\Configuration::$data_path . '/migrations_version.txt';
        touch($migration_file);
        \Minz\Database::create();

        $this->appRun('cli', '/system/migrate');
        $response = $this->appRun('cli', '/system/migrate');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Your system is already up to date.');
    }

    public function testMigrateFailsWithAFailingMigrationReturningFalse()
    {
        $migration_file = \Minz\Configuration::$data_path . '/migrations_version.txt';
        touch($migration_file);
        $failing_migration_path = \Minz\Configuration::$app_path . '/src/migrations/TheFailingMigrationWithFalse.php';
        $failing_migration_content = <<<'PHP'
            <?php
            namespace Website\migrations;
            class TheFailingMigrationWithFalse
            {
                public function migrate()
                {
                    return false;
                }
            }
            PHP;
        file_put_contents($failing_migration_path, $failing_migration_content);

        \Minz\Database::create();

        $response = $this->appRun('cli', '/system/migrate');

        @unlink($failing_migration_path);

        $this->assertResponseCode($response, 500);
        $this->assertResponseContains($response, 'TheFailingMigrationWithFalse: KO');
    }

    public function testMigrateFailsWithAFailingMigrationReturningAMessage()
    {
        $migration_file = \Minz\Configuration::$data_path . '/migrations_version.txt';
        touch($migration_file);
        $failing_migration_path = \Minz\Configuration::$app_path . '/src/migrations/TheFailingMigrationWithMessage.php';
        $failing_migration_content = <<<'PHP'
            <?php
            namespace Website\migrations;
            class TheFailingMigrationWithMessage
            {
                public function migrate()
                {
                    throw new \Exception('this test fails :(');
                }
            }
            PHP;
        file_put_contents($failing_migration_path, $failing_migration_content);

        \Minz\Database::create();

        $response = $this->appRun('cli', '/system/migrate');

        @unlink($failing_migration_path);

        $this->assertResponseCode($response, 500);
        $this->assertResponseContains($response, 'TheFailingMigrationWithMessage: this test fails :(');
    }

    public function testMigrateFailsIfNotInitialized()
    {
        $migration_file = \Minz\Configuration::$data_path . '/migrations_version.txt';

        $this->assertFalse(file_exists($migration_file));

        $response = $this->appRun('cli', '/system/migrate');

        $this->assertResponseCode($response, 500);
        $this->assertResponseContains($response, 'You must initialize the system first.');
    }

    public function testAllMigrationsCanBeRollback()
    {
        $migrations_path = \Minz\Configuration::$app_path . '/src/migrations';
        $migrations_version_path = \Minz\Configuration::$data_path . '/migrations_version.txt';
        $number_migrations = count(scandir($migrations_path)) - 2;
        \Minz\Database::create();
        $migrator = new \Minz\Migrator($migrations_path);
        $migrator->migrate();
        @file_put_contents($migrations_version_path, $migrator->version());

        $response = $this->appRun('cli', '/system/rollback', [
            'steps' => $number_migrations,
        ]);

        $this->assertResponseCode($response, 200);
    }

    public function testRollbackWithAFailingRollback()
    {
        $migration_file = \Minz\Configuration::$data_path . '/migrations_version.txt';
        touch($migration_file);
        $failing_migration_path = \Minz\Configuration::$app_path . '/src/migrations/TheFailingRollbackWithFalse.php';
        $failing_migration_content = <<<'PHP'
            <?php
            namespace Website\migrations;
            class TheFailingRollbackWithFalse
            {
                public function migrate()
                {
                    return true;
                }
                public function rollback()
                {
                    return false;
                }
            }
            PHP;
        file_put_contents($failing_migration_path, $failing_migration_content);
        file_put_contents($migration_file, 'TheFailingRollbackWithFalse');

        $response = $this->appRun('cli', '/system/rollback');

        @unlink($failing_migration_path);

        $this->assertResponseCode($response, 500);
        $this->assertResponseContains($response, 'TheFailingRollbackWithFalse: KO');
    }
}
