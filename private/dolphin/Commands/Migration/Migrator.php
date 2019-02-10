<?php

namespace Dolphin\Commands\Migration;


use Dolphin\Console;
use WofhTools\Core\AppSettings;


/**
 * Dolphin command line interface
 * Class Migrator
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016—2019 delphinpro
 * @license     Licensed under the MIT license
 * @package     Dolphin\Commands\Migration
 */
class Migrator
{
    /** @var string */
    private $migrationsDir;

    /** @var string */
    private $migrationTable;

    /** @var \Illuminate\Database\Connection */
    private $db;

    /** @var array */
    private $params;

    /** @var Console */
    private $console;


    public function __construct(
        string $migrationsDir,
        array $params,
        Console $console,
        AppSettings $config,
        \Illuminate\Database\Connection $db
    ) {
        $this->migrationsDir = rtrim($migrationsDir, '\\/');
        $this->migrationTable = 'migrations';
        $this->params = $params;
        $this->console = $console;
        $this->db = $db;
    }


    public function create()
    {
        $description = array_key_exists('desc', $this->params) ? $this->params['desc'] : '';

        $savedTimeZone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $time = time();
        $subDir = date('Y_m', $time);
        $date = date('Ymd_His', $time);
        $classNameOfMigration = 'Migration_'.$date;
        date_default_timezone_set($savedTimeZone);

        $unitContent = <<<CONTENT
<?php


use Dolphin\\Commands\\Migration\\BaseMigration;


class $classNameOfMigration extends BaseMigration
{
    public function up()
    {
        
    }


    public function down()
    {
        
    }
    

    public function description()
    {
        return '$description';
    }
}

CONTENT;

        if (!is_dir($this->migrationsDir)) {
            $this->console->error('Migrations directory not exists: '.$this->migrationsDir);
            $this->console->stop();
        }

        if (!is_dir($this->migrationsDir.DIRECTORY_SEPARATOR.$subDir)) {
            if (!mkdir($this->migrationsDir.DIRECTORY_SEPARATOR.$subDir, 0777)) {
                $this->console->error('Migration create: FAIL');
                $this->console->stop();
            }
        }

        $fileName = $this->migrationsDir
            .DIRECTORY_SEPARATOR.$subDir
            .DIRECTORY_SEPARATOR.$classNameOfMigration.'.php';
        file_put_contents($fileName, $unitContent);

        $this->console->write('Migration created: ', Console::GREEN, false);
        $this->console->write($classNameOfMigration, Console::GREEN, false);
        $this->console->write(' "'.$description.'"', Console::CYAN);
    }


    public function migrate()
    {
        $count = 0;
        $this->checkMigrationTable();
        $lastId = $this->getLastAppliedMigration();
        $message = 'Last applied migration: '.($lastId === false ? 'None' : $lastId);
        $this->console->write($message, Console::YELLOW);

        $dirs = scandir($this->migrationsDir, SCANDIR_SORT_ASCENDING);
        if ($dirs === false) {
            $this->console->stop("Error scandir({$this->migrationsDir})", Console::RED);
        }

        $dirs = array_filter($dirs, function ($item) {
            return ($item != '.') and ($item != '..');
        });

        try {

            $this->db->beginTransaction();

            try {
                foreach ($dirs as $dir) {
                    $fullNameDir = $this->migrationsDir.DIRECTORY_SEPARATOR.$dir;
                    $files = scandir($fullNameDir, SCANDIR_SORT_ASCENDING);
                    if ($files === false) {
                        $this->db->rollBack();
                        $this->console->stop("Error scandir({$fullNameDir})", Console::RED);
                    }
                    $files = array_filter($files,
                        function ($item) {
                            return ($item != '.') and ($item != '..');
                        });

                    foreach ($files as $file) {
                        $className = '\\'.str_replace('.php', '', $file);
                        $id = preg_replace('/\D/', '', $className);

                        if ($id <= $lastId) {
                            continue;
                        }

                        /** @noinspection PhpIncludeInspection */
                        require_once $fullNameDir.DIRECTORY_SEPARATOR.$file;

                        /** @var \dolphin\Commands\Migration\BaseMigration $object */
                        $object = new $className();
                        $object->up();

                        $this->db->table($this->migrationTable)->insert(['id' => $id]);
                        $message = 'Apply migration: '.$className.' OK!';
                        $this->console->write($message, Console::GREEN);
                        $count++;
                        unset($object);
                    }
                }

                $this->db->commit();
                $this->console->write('Applied migrations: '.$count);

            } catch (\Exception $e) {
                $this->console->error($e->getMessage());
                $this->console->error($e->getTraceAsString());
                $this->db->rollBack();
            }
        } catch (\Exception $e) {
            $this->console->error($e->getMessage());
            $this->console->error($e->getTraceAsString());
        }
    }


    public function rollback()
    {
        $this->checkMigrationTable();
        $lastId = $this->getLastAppliedMigration();
        $message = 'Last applied migration: '.($lastId === false ? 'None' : $lastId);
        $this->console->write($message, Console::YELLOW);

        if ($lastId === false) {
            $this->console->stop('Migration rollback: 0');
        }

        $subDir = preg_replace('/^(\d{4})(\d{2})\d+$/', '\\1_\\2', $lastId);
        $file = preg_replace('/^(\d{8})(\d{6})$/', 'Migration_\\1_\\2.php', $lastId);

        $fullNameDir = $this->migrationsDir.DIRECTORY_SEPARATOR.$subDir;
        $fileName = $fullNameDir.DIRECTORY_SEPARATOR.$file;

        if (!is_file($fileName) || !is_readable($fileName)) {
            $this->console->error('Migration file not found!');
            $this->console->error($fileName);
            $this->console->stop();
        }

        $className = '\\'.str_replace('.php', '', $file);

        /** @noinspection PhpIncludeInspection */
        require_once $fullNameDir.DIRECTORY_SEPARATOR.$file;

        /** @var \dolphin\Commands\Migration\BaseMigration $object */
        $object = new $className();
        $object->down();

        $this->db->table($this->migrationTable)->delete($lastId);
        $this->console->write('Rollback migration: '.$className.' OK!', Console::GREEN);
        unset($object);
    }


    public function status()
    {
        $this->checkMigrationTable();
        $lastId = $this->getLastAppliedMigration();
        $d = $this->getAllMigrationList();

        $this->console->write('Migrations:');
        foreach ($d as $item) {
            $subDir = preg_replace('/^(\d{4})(\d{2})\d+$/', '\\1_\\2', $item);
            $name = preg_replace('/^(\d{8})(\d{6})$/', '\\1_\\2', $item);
            $file = 'Migration_'.$name.'.php';
            $className = '\\Migration_'.$name.'';
            $fullNameDir = $this->migrationsDir.DIRECTORY_SEPARATOR.$subDir;
            $filename = $fullNameDir.DIRECTORY_SEPARATOR.$file;

            /** @noinspection PhpIncludeInspection */
            require_once $filename;
            /** @var \dolphin\Commands\Migration\BaseMigration $object */
            $object = new $className();
            $desc = $object->description();
            unset($object);

            if ($item == $lastId) {
                $this->console->write("    $name <--", Console::GREEN, false);
            } elseif ($item < $lastId) {
                $this->console->write("    $name [+]", Console::GREEN, false);
            } else {
                $this->console->write("    $name [?]", Console::RED, false);
            }
            $this->console->write("  \"$desc\"", Console::CYAN);
        }
        $this->console->write('Last applied migration: '.($lastId === false ? 'None' : $lastId));
    }


    //== ====================================================================================== ==//
    //== Private methods
    //== ====================================================================================== ==//


    private function getAllMigrationList()
    {
        $result = [];

        $dirs = scandir($this->migrationsDir, SCANDIR_SORT_ASCENDING);
        if ($dirs === false) {
            $this->console->stop("Error scandir({$this->migrationsDir})", Console::RED);
        }

        $dirs = array_filter($dirs, function ($item) {
            return ($item != '.') and ($item != '..');
        });

        foreach ($dirs as $dir) {
            $fullNameDir = $this->migrationsDir.DIRECTORY_SEPARATOR.$dir;
            $files = scandir($fullNameDir, SCANDIR_SORT_ASCENDING);

            if ($files === false) {
                $this->console->stop("Error scandir({$fullNameDir})", Console::RED);
            }

            $files = array_filter($files,
                function ($item) {
                    return ($item != '.') and ($item != '..');
                });

            foreach ($files as $file) {
                $className = '\\'.str_replace('.php', '', $file);
                $id = preg_replace('/\D/', '', $className);
                $result[] = $id;
            }
        }

        return $result;
    }


    private function getLastAppliedMigration()
    {
        $d = $this->db->table($this->migrationTable)->select('id')->max('id');

        if (empty($d)) {
            return false;
        }

        return $d;
    }


    private function checkMigrationTable()
    {
        $query = $this->db->raw("
          CREATE TABLE IF NOT EXISTS `{$this->migrationTable}` (
              `id` BIGINT(20) NOT NULL,
              PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ")->getValue();
        $this->db->statement($query);
    }
}
