<?php

namespace Dolphin\Commands\Migration;


use Dolphin\Cli;
use WofhTools\Core\AppSettings;


require_once DIR_ROOT.'/private/bootstrap/global_functions.php';


/**
 * Dolphin command line interface
 * Class Migrator
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016—2019 delphinpro
 * @license     Licensed under the MIT license
 * @package     Dolphin\Commands\Migration
 */
class Migrator extends Cli
{
    /** @var string */
    protected $migrationsDir;

    /** @var string */
    protected $migrationTable;

    /** @var \Illuminate\Database\Connection */
    protected $db;

    protected $arguments;


    public function __construct($migrationsDir, $arguments = [])
    {
        $this->arguments = $arguments;
        $config = AppSettings::loadConfig(DIR_CONFIG, DIR_ROOT);

        $this->migrationsDir = rtrim($migrationsDir, DIRECTORY_SEPARATOR);
        $this->migrationTable = 'migrations';

        $capsule = bootEloquent($config->db);

        $this->db = $capsule->getConnection();
        $this->db->enableQueryLog();
    }


    /**
     * @throws \Exception
     */
    public function create()
    {
        if (!is_array($this->arguments)) {
            throw new \Exception('Invalid argument: array expected, '.gettype($this->arguments).' given');
        }

        $message = array_key_exists('desc', $this->arguments) ? $this->arguments['desc'] : '';

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
        return '$message';
    }
}

CONTENT;

        if (is_dir($this->migrationsDir)) {
            if (!is_dir($this->migrationsDir.DIRECTORY_SEPARATOR.$subDir)) {
                if (!mkdir($this->migrationsDir.DIRECTORY_SEPARATOR.$subDir, 0777)) {
                    $this->writeLn('Migration create: FAIL', Cli::FONT_RED);

                    return;
                }
            }

            $fileName = $this->migrationsDir
                .DIRECTORY_SEPARATOR.$subDir
                .DIRECTORY_SEPARATOR.$classNameOfMigration.'.php';
            file_put_contents($fileName, $unitContent);

            $this->write('Migration created: ', Cli::FONT_GREEN);
            $this->write($classNameOfMigration, [Cli::FONT_GREEN, Cli::FONT_BOLD]);
            $this->writeLn(' "'.$message.'"', Cli::FONT_CYAN);
        } else {
            $this->writeLn('Migration directory not exists: '.$this->migrationsDir, Cli::FONT_RED);
        }
    }


    public function migrate()
    {
        $this->checkMigrationTable();
        $lastId = $this->getLastAppliedMigration();
        $this->writeLn('Last applied migration: '.($lastId === false ? 'None' : $lastId),
            Cli::FONT_YELLOW);

        $dirs = scandir($this->migrationsDir, SCANDIR_SORT_ASCENDING);
        if ($dirs === false) {
            $this->halt("Error scandir({$this->migrationsDir})", Cli::FONT_RED);
        }

        $dirs = array_filter($dirs,
            function ($item) {
                return ($item != '.') and ($item != '..');
            });

        $this->db->beginTransaction();
        $count = 0;
        try {
            foreach ($dirs as $dir) {
                $fullNameDir = $this->migrationsDir.DIRECTORY_SEPARATOR.$dir;
                $files = scandir($fullNameDir, SCANDIR_SORT_ASCENDING);
                if ($files === false) {
                    $this->db->rollBack();
                    $this->halt("Error scandir({$fullNameDir})", Cli::FONT_RED);
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
                    $this->writeLn('Apply migration: '.$className.' OK!', Cli::FONT_GREEN);
                    $count++;
                    unset($object);
                }
            }
            $this->db->commit();
            $this->writeLn('Applied migrations: '.$count);
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->writeLn($e->getMessage(), [Cli::FONT_BOLD, Cli::FONT_RED]);
        }
    }


    public function rollback()
    {
        $this->checkMigrationTable();
        $lastId = $this->getLastAppliedMigration();
        $this->writeLn('Last applied migration: '.($lastId === false ? 'None' : $lastId),
            Cli::FONT_YELLOW);

        if ($lastId === false) {
            $this->writeLn('Migration rollback: 0');

            return;
        }

        $subDir = preg_replace('/^(\d{4})(\d{2})\d+$/', '\\1_\\2', $lastId);
        $file = preg_replace('/^(\d{8})(\d{6})$/', 'Migration_\\1_\\2.php', $lastId);

        $fullNameDir = $this->migrationsDir.DIRECTORY_SEPARATOR.$subDir;
        $fileName = $fullNameDir.DIRECTORY_SEPARATOR.$file;

        if (!is_file($fileName) || !is_readable($fileName)) {
            $this->halt('Migration file not found!', Cli::FONT_RED);
        }

        $className = '\\'.str_replace('.php', '', $file);

        /** @noinspection PhpIncludeInspection */
        require_once $fullNameDir.DIRECTORY_SEPARATOR.$file;

        /** @var \dolphin\Commands\Migration\BaseMigration $object */
        $object = new $className();
        $object->down();

        $this->db->table($this->migrationTable)->delete($lastId);
        $this->writeLn('Rollback migration: '.$className.' OK!', Cli::FONT_GREEN);
        unset($object);
    }


    public function status()
    {
        $this->checkMigrationTable();
        $lastId = $this->getLastAppliedMigration();
        $d = $this->getAllMigrationList();

        $this->writeLn('Migrations:');
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
                $this->write('    '.$name.' '.'<--', [Cli::FONT_BOLD, Cli::FONT_GREEN]);
            } elseif ($item < $lastId) {
                $this->write('    '.$name.' '.'[+]', Cli::FONT_GREEN);
            } else {
                $this->write('    '.$name.' '.'[?]', Cli::FONT_RED);
            }
            $this->writeLn('  "'.$desc.'"', Cli::FONT_CYAN);
        }
        $this->writeLn('Last applied migration: '.($lastId === false ? 'None' : $lastId));
    }


    private function getAllMigrationList()
    {
        $result = [];

        $dirs = scandir($this->migrationsDir, SCANDIR_SORT_ASCENDING);
        if ($dirs === false) {
            $this->halt("Error scandir({$this->migrationsDir})", Cli::FONT_RED);
        }

        $dirs = array_filter($dirs, function ($item) {
            return ($item != '.') and ($item != '..');
        });

        foreach ($dirs as $dir) {
            $fullNameDir = $this->migrationsDir.DIRECTORY_SEPARATOR.$dir;
            $files = scandir($fullNameDir, SCANDIR_SORT_ASCENDING);

            if ($files === false) {
                $this->halt("Error scandir({$fullNameDir})", Cli::FONT_RED);
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
