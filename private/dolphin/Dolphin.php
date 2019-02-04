<?php

namespace Dolphin;


/**
 * Dolphin command line interface
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016 delphinpro
 * @license     Licensed under the MIT license
 * @package     Dolphin
 */
class Dolphin extends Cli
{
    /** @var array $arguments */
    private $arguments;


    public function __construct()
    {
        $this->arguments = $_SERVER['argv'];
        array_shift($this->arguments);
    }


    public function run()
    {
        $this->write("Dolphin command line interface v1.0", Cli::FONT_BLUE);
        $this->writeLn(" [php ".PHP_VERSION."]", Cli::FONT_GREEN);
        $this->checkPhpVersion();
        $this->checkSApi();
        $this->checkArgs();
        $this->execCommand($this->arguments[0]);
    }


    private function execCommand($commandName)
    {
        list ($commandName) = explode(':', $commandName);

        $className = '\\Dolphin\\Commands\\'.ucfirst(strtolower($commandName));
        if (class_exists($className)) {
            $command = new $className();
            if ($command instanceof BaseCommand) {
                $command->execute($this->arguments);
            } else {
                $this->halt("$className not instance of CommandInterface", Cli::FONT_RED);
            }
        } else {
            $this->write("Unknown command: ", Cli::FONT_RED);
            $this->writeLn('<'.$commandName.'>', [Cli::FONT_BOLD, Cli::FONT_RED]);
            $this->stop();
        }
    }


    private function checkPhpVersion()
    {
        if (version_compare(PHP_VERSION, '7.2') < 0) {
            $this->halt(
                sprintf('Need version PHP 7.2 or higher. Your version: %s', PHP_VERSION),
                Cli::FONT_RED
            );
        }
    }


    private function checkSApi()
    {
        if (PHP_SAPI !== 'cli') {
            $this->halt('Dolphin can run only cli.');
        }
    }


    private function checkArgs()
    {
        if (count($this->arguments) < 1) {
            $files = scandir(__DIR__.DIRECTORY_SEPARATOR.'Commands');
            $commands = [];
            $maxLength = 0;
            foreach ($files as $filename) {
                if ($filename == '.' or $filename == '..') {
                    continue;
                }
                if (!is_file(
                    __DIR__.DIRECTORY_SEPARATOR.'Commands'.DIRECTORY_SEPARATOR.$filename
                )) {
                    continue;
                }

                $commandName = str_replace('.php', '', strtolower($filename));
                $className = '\\Dolphin\\Commands\\'.ucfirst(strtolower($commandName));
                if (class_exists($className)) {
                    $command = new $className();
                    if ($command instanceof BaseCommand) {
                        if (strlen($commandName) > $maxLength) {
                            $maxLength = strlen($commandName);
                        }
                        $commands[] = [
                            'command'     => $commandName,
                            'description' => $command->getDescription(),
                        ];
                    }
                    unset($command);
                }
            }

            $s = '  php dolphin ';
            $maxLength += strlen($s) + 2;

            $this->writeLn('Available commands:');
            foreach ($commands as $cmd) {
                $this->write(str_pad($s.$cmd['command'], $maxLength, ' '), Cli::FONT_CYAN);
                $this->writeLn($cmd['description']);
            }

            $this->stop();
        }
    }
}
