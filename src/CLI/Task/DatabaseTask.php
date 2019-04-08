<?php

namespace Project\CLI\Task;

use DateTime;
use SplFileInfo;
use Phalcon\Config;
use Phalcon\Cli\Task;
use Phalcon\Db\Column;
use League\CLImate\CLImate;
use RecursiveDirectoryIterator;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Project\CLI\Model\SchemaVersion;

/**
 * Class DatabaseTask
 * @package Project\CLI\Task
 * @property Mysql $db
 * @property Config $config
 * @property CLImate $output
 */
class DatabaseTask extends Task
{
    /**
     * bin/console database:initialize
     * Initializes the app's database, to be used only once
     */
    public function initializeAction()
    {
        $this->output->info('Initializing the database...');

        if ($this->db->tableExists('schema_version')) {
            $this->output->error('Database already initialized!');
            return;
        }

        $result = $this->db->createTable(
            'schema_version',
            null,
            [
                'columns' => [
                    new Column(
                        'version',
                        [
                            'type'          => Column::TYPE_VARCHAR,
                            'size'          => 14,
                            'notNull'       => true,
                            'autoIncrement' => false,
                            'primary'       => true
                        ]
                    )
                ]
            ]
        );

        if ($result) {
            $this->output->info('Database initialized!');
            $this->upgradeAction();
            return;
        }

        $this->output->error("Unable to initialize the database. Check log.");
    }

    /**
     * bin/console database:upgrade
     * Imports the new schemas from the Schemas folder
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function upgradeAction()
    {
        $this->output->info('Upgrading the database...');
        if (!$this->db->tableExists('schema_version')) {
            $this->output->error('Database not initialized! Please use database:initialize command!');
            return;
        }

        $schemaDir = project_root($this->config->path('application.schemaDir'));

        $schemasDirIterator = new RecursiveDirectoryIterator(
            $schemaDir,
            RecursiveDirectoryIterator::SKIP_DOTS
        );

        if (!$this->verifyCommand('mysql')) {
            $this->output->error('MySQL shell command needs to be available for this to work.');
            return;
        }

        $dbConf = $this->config->get('database')->toArray();
        $dbConf['password'] = str_replace('$', '\\$', $dbConf['password']);

        $mysqlCommand = "mysql --user={$dbConf['username']} --password=\"{$dbConf['password']}\" "
                        ."-h {$dbConf['host']} -D {$dbConf['dbname']} < ";

        /** @var SplFileInfo $fileInfo */
        foreach ($schemasDirIterator as $fileInfo) {
            // Skipping non-SQL files
            if ($fileInfo->getExtension() !== 'sql') {
                continue;
            }
            $fileVersion = substr($fileInfo->getFilename(), 0, -4);

            // Skipping files not matching the naming schema
            if (!preg_match('/^[0-9]{14}$/', $fileVersion)) {
                continue;
            }

            if (SchemaVersion::findFirstByVersion($fileVersion) === false) {
                $this->output->darkGray("Upgrading to $fileVersion...");
                $feedback = shell_exec($mysqlCommand .'"'. $fileInfo->getPathname() .'" 2>&1');

                if (!is_null($feedback)) {
                    $messages = explode("\n", trim($feedback));

                    foreach ($messages as $message) {
                        if (substr($message, 0, 5) === "ERROR") {
                            $this->output->error($message);
                            return;
                        }
                    }
                }

                $newVersion = new SchemaVersion(['version' => $fileVersion]);
                $newVersion->create();
                $this->output->info("Upgrade successful!");
            }
        }
    }

    /**
     * bin/console database:touch
     * Creates an empty sql file for schema changes to be added
     */
    public function touchAction()
    {
        $this->output->info('Creating an upgrade file...');

        $schemaDir = project_root($this->config->path('application.schemaDir'));
        $fileName = (new DateTime())->format('YmdHis') .'.sql';

        if (touch($schemaDir . $fileName)) {
            $this->output->darkGray($fileName . ' created.');
            return;
        }

        $this->output->red('File creation error, please check permissions.');
    }

    /**
     * Checks availability of a shell command
     * @param string $command
     * @return bool
     */
    function verifyCommand(string $command): bool
    {
        $test = strpos(PHP_OS, 'WIN') === 0
            ? 'where'
            : 'command -v';
        return is_executable(trim((string) shell_exec("$test $command")));
    }
}
