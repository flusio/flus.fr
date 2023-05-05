<?php

namespace Website\cli;

class MigrationsTest extends \PHPUnit\Framework\TestCase
{
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;

    /**
     * @before
     */
    public function uninstall(): void
    {
        $migration_file = \Minz\Configuration::$data_path . '/migrations_version.txt';
        @unlink($migration_file);
        \Minz\Database::reset();
    }

    public function testAllMigrationsCanBeApplied(): void
    {
        $migration_file = \Minz\Configuration::$data_path . '/migrations_version.txt';
        $migrations_path = \Minz\Configuration::$app_path . '/src/migrations';
        touch($migration_file);
        $migrator = new \Minz\Migration\Migrator($migrations_path);
        $last_migration_version = $migrator->lastVersion();
        $expected_output = [];
        foreach ($migrator->migrations() as $version => $migration) {
            $expected_output[] = "{$version}: OK";
        }
        $expected_output = implode("\n", $expected_output);

        $response = $this->appRun('CLI', '/migrations/setup');

        $this->assertResponseCode($response, 200);
        $current_migration_version = @file_get_contents($migration_file);
        $this->assertSame($last_migration_version, $current_migration_version);
        $this->assertResponseEquals($response, $expected_output);
    }

    public function testAllMigrationsCanBeRollback(): void
    {
        $migrations_path = \Minz\Configuration::$app_path . '/src/migrations';
        $migrations_version_path = \Minz\Configuration::$data_path . '/migrations_version.txt';
        $migrator = new \Minz\Migration\Migrator($migrations_path);
        $migrator->migrate();
        @file_put_contents($migrations_version_path, $migrator->version());
        $number_migrations = count($migrator->migrations());
        $expected_output = [];
        foreach ($migrator->migrations(reverse: true) as $version => $migration) {
            $expected_output[] = "{$version}: OK";
        }
        $expected_output = implode("\n", $expected_output);

        $response = $this->appRun('CLI', '/migrations/rollback', [
            'steps' => $number_migrations,
        ]);

        $this->assertResponseCode($response, 200);
        $this->assertResponseEquals($response, $expected_output);
    }
}
