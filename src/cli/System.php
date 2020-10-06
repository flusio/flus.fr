<?php

namespace Website\cli;

class System
{
    /**
     * Initialize the database and set the migration version.
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function init()
    {
        $app_path = \Minz\Configuration::$app_path;
        $data_path = \Minz\Configuration::$data_path;
        $schema_path = $app_path . '/src/schema.sql';
        $migrations_path = $app_path . '/src/migrations';
        $migrations_version_path = $data_path . '/migrations_version.txt';

        if (file_exists($migrations_version_path)) {
            return \Minz\Response::text(500, 'The system is already initialized.'); // @codeCoverageIgnore
        }

        $schema = file_get_contents($schema_path);
        $database = \Minz\Database::get();
        $database->exec($schema);

        $migrator = new \Minz\Migrator($migrations_path);
        $version = $migrator->lastVersion();
        $saved = @file_put_contents($migrations_version_path, $version);
        if ($saved === false) {
            $text = "Cannot save the migrations version file (version: {$version})."; // @codeCoverageIgnore
            return \Minz\Response::text(500, $text); // @codeCoverageIgnore
        }

        return \Minz\Response::text(200, 'The system has been initialized.');
    }

    /**
     * Execute the migrations under src/migrations/. The version is stored in
     * data/migrations_version.txt.
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function migrate($request)
    {
        $app_path = \Minz\Configuration::$app_path;
        $data_path = \Minz\Configuration::$data_path;
        $migrations_path = $app_path . '/src/migrations';
        $migrations_version_path = $data_path . '/migrations_version.txt';

        if (!file_exists($migrations_version_path)) {
            return \Minz\Response::text(500, 'You must initialize the system first.');
        }

        $migration_version = @file_get_contents($migrations_version_path);
        if ($migration_version === false) {
            return \Minz\Response::text(500, 'Cannot read the migrations version file.'); // @codeCoverageIgnore
        }

        $migrator = new \Minz\Migrator($migrations_path);
        $migration_version = trim($migration_version);
        if ($migration_version) {
            $migrator->setVersion($migration_version);
        }

        if ($migrator->upToDate()) {
            return \Minz\Response::text(200, 'Your system is already up to date.');
        }

        $results = $migrator->migrate();

        $new_version = $migrator->version();
        $saved = @file_put_contents($migrations_version_path, $new_version);
        if ($saved === false) {
            $text = "Cannot save the migrations version file (version: {$version})."; // @codeCoverageIgnore
            return \Minz\Response::text(500, $text); // @codeCoverageIgnore
        }

        $has_error = false;
        $text = '';
        foreach ($results as $migration => $result) {
            if ($result === true) {
                $result = 'OK';
            } elseif ($result === false) {
                $result = 'KO';
            }

            if ($result !== 'OK') {
                $has_error = true;
            }

            $text .= "\n" . $migration . ': ' . $result;
        }

        return \Minz\Response::text($has_error ? 500 : 200, $text);
    }

    /**
     * Execute the rollback of the latest migrations.
     *
     * @request_param integer steps (default is 1)
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function rollback($request)
    {
        $app_path = \Minz\Configuration::$app_path;
        $data_path = \Minz\Configuration::$data_path;
        $migrations_path = $app_path . '/src/migrations';
        $migrations_version_path = $data_path . '/migrations_version.txt';

        $migration_version = @file_get_contents($migrations_version_path);
        if ($migration_version === false) {
            return \Minz\Response::text(500, 'Cannot read the migrations version file.'); // @codeCoverageIgnore
        }

        $migrator = new \Minz\Migrator($migrations_path);
        $migration_version = trim($migration_version);
        if ($migration_version) {
            $migrator->setVersion($migration_version);
        }

        $steps = intval($request->param('steps', 1));
        $results = $migrator->rollback($steps);

        $new_version = $migrator->version();
        $saved = @file_put_contents($migrations_version_path, $new_version);
        if ($saved === false) {
            $text = "Cannot save the migrations version file (version: {$version})."; // @codeCoverageIgnore
            return \Minz\Response::text(500, $text); // @codeCoverageIgnore
        }

        $has_error = false;
        $text = '';
        foreach ($results as $migration => $result) {
            if ($result === false) {
                $result = 'KO';
            } elseif ($result === true) {
                $result = 'OK';
            }

            if ($result !== 'OK') {
                $has_error = true;
            }

            $text .= "\n" . $migration . ': ' . $result;
        }
        return \Minz\Response::text($has_error ? 500 : 200, $text);
    }
}
