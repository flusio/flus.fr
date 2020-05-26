<?php

namespace Website;

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
        $schema_path = $app_path . '/src/schema.sql';
        $migrations_path = $app_path . '/src/migrations';
        $migrations_version_path = $app_path . '/data/migrations_version.txt';

        if (file_exists($migrations_version_path)) {
            $output = new \Minz\Output\Text(
                "data/migrations_version.txt file exists, the system is already initialized.\n"
            );
            return new \Minz\Response(500, $output);
        }

        $schema = file_get_contents($schema_path);
        $database = \Minz\Database::get();
        $database->exec($schema);

        $migrator = new \Minz\Migrator($migrations_path);
        $version = $migrator->lastVersion();
        $saved = @file_put_contents($migrations_version_path, $version);
        if ($saved === false) {
            $output = new \Minz\Output\Text(
                "Cannot save data/migrations_version.txt file ({$version}).\n"
            );
            return new \Minz\Response(500, $output);
        }

        $output = new \Minz\Output\Text("The system has been initialized.\n");
        return new \Minz\Response(200, $output);
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
        $migrations_path = $app_path . '/src/migrations';
        $migrations_version_path = $app_path . '/data/migrations_version.txt';

        if (!file_exists($migrations_version_path)) {
            $output = new \Minz\Output\Text(
                "data/migrations_version.txt file does not exist, you must initialize the system first.\n"
            );
            return new \Minz\Response(500, $output);
        }

        $migration_version = @file_get_contents($migrations_version_path);
        if ($migration_version === false) {
            $output = new \Minz\Output\Text("Cannot read data/migrations_version.txt file.\n");
            return new \Minz\Response(500, $output);
        }

        $migrator = new \Minz\Migrator($migrations_path);
        $migration_version = trim($migration_version);
        if ($migration_version) {
            $migrator->setVersion($migration_version);
        }

        if ($migrator->upToDate()) {
            $output = new \Minz\Output\Text("Your system is already up to date.\n");
            return new \Minz\Response(200, $output);
        }

        $results = $migrator->migrate();

        $new_version = $migrator->version();
        $saved = @file_put_contents($migrations_version_path, $new_version);
        if ($saved === false) {
            $output = new \Minz\Output\Text(
                "Cannot save data/migrations_version.txt file ({$version}).\n"
            );
            return new \Minz\Response(500, $output);
        }

        $text = "Migrations results:\n";
        foreach ($results as $migration => $result) {
            if ($result === true) {
                $result = 'OK';
            } elseif ($result === false) {
                $result = 'KO';
            }
            $text = $text . $migration . ': ' . $result . "\n";
        }
        $output = new \Minz\Output\Text($text);
        return new \Minz\Response(200, $output);
    }
}
