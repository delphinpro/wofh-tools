<?php

namespace Dolphin;


use Psr\Container\ContainerInterface;
use Slim\Container;
use WofhTools\Core\AppSettings;
use WofhTools\Helpers\FileSystem;
use WofhTools\Tools\Wofh;


/**
 * Dolphin command line interface
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016 delphinpro
 * @license     Licensed under the MIT license
 * @package     Dolphin
 */
class Dolphin extends DolphinContainer
{
    /** @var string */
    private $commandsDirectory;

    /** @var string */
    private $commandName;

    /** @var string */
    private $commandAction;


    /**
     * @param string $configLocation
     *
     * @return Dolphin
     */
    public static function getInstance(string $configLocation)
    {
        $container = new Container();

        $container['console'] = function () {
            return new Console();
        };

        loadGlobalConfiguration($configLocation);

        $container['config'] = function () use ($configLocation) {
            return new AppSettings(getConfigFromEnv());
        };

        $container['http'] = function () {
            return new \WofhTools\Helpers\Http();
        };

        $container['json'] = function () {
            return new \WofhTools\Helpers\Json();
        };

        $container['db'] = function ($c) {
            $capsule = bootEloquent($c['config']->db);
            $db = $capsule->getConnection();
            //$db->enableQueryLog();

            return $db;
        };

        $container['wofh'] = function ($c) {
            return new Wofh($c['http'], $c['json']);
        };

        $container['fs'] = function () {
            return new FileSystem(DIR_ROOT);
        };

        return new Dolphin($container);
    }


    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->commandsDirectory = __DIR__.DIRECTORY_SEPARATOR.'Commands';
    }


    public function run()
    {
        try {

            $this->printWelcomeMessage();
            $this->checkPhpVersion();
            $this->checkSApi();
            $this->processArguments();
            $this->execCommand();

        } catch (\Exception $e) {

            $this->console->error($e->getMessage());
            $this->console->error($e->getTraceAsString());

        }
    }


    //== ====================================================================================== ==//
    //== Private methods
    //== ====================================================================================== ==//


    private function printWelcomeMessage()
    {
        $title = 'Dolphin command line interface v1.0';
        $subtitle = ' [php '.PHP_VERSION.']';
        $color = Console::BLUE;

        $this->console->lineDouble(mb_strlen($title.$subtitle) + 1, $color);
        $this->console->write($title, $color, false);
        $this->console->write($subtitle, Console::GREEN);
        $this->console->lineDouble(mb_strlen($title.$subtitle) + 1, $color);
    }


    private function checkPhpVersion()
    {
        if (version_compare(PHP_VERSION, '7.2') < 0) {
            $message = sprintf('Need version PHP 7.2 or higher. Your version: %s', PHP_VERSION);
            $this->console->stop($message, Console::RED);
        }
    }


    private function checkSApi()
    {
        if (PHP_SAPI !== 'cli') {
            $this->console->stop('Dolphin can run only cli.');
        }
    }


    private function processArguments()
    {
        $arguments = $_SERVER['argv'];
        array_shift($arguments);

        if (count($arguments) < 1) {
            $this->printCommandList();
            $this->console->stop();
        }

        $this->container['arguments'] = function () use ($arguments) {
            return $arguments;
        };

        $command = array_shift($arguments);
        list($this->commandName, $this->commandAction) = explode(':', $command);

        if (!$this->commandAction) {
            $this->commandAction = 'help';
        }
    }


    private function printCommandList()
    {
        $files = scandir($this->commandsDirectory);
        $commands = [];
        $maxLen = 0;

        foreach ($files as $filename) {

            if ($filename == '.' or $filename == '..') {
                continue;
            }

            if (!is_file($this->commandsDirectory.DIRECTORY_SEPARATOR.$filename)) {
                continue;
            }

            $commandName = str_replace('.php', '', strtolower($filename));
            $className = '\\Dolphin\\Commands\\'.ucfirst(strtolower($commandName));
            $methodName = 'getDescription';

            if (method_exists($className, $methodName)) {
                $maxLen = max(strlen($commandName), $maxLen);
                $commands[] = [
                    'command'     => $commandName,
                    'description' => $className::$methodName(),
                ];
            }
        }

        $s = '  php dolphin ';
        $maxLen += strlen($s) + 2;

        $this->console->write('Available commands:');

        foreach ($commands as $cmd) {
            $this->console->write(str_pad($s.$cmd['command'], $maxLen, ' '), Console::CYAN, false);
            $this->console->write($cmd['description']);
        }
    }


    private function execCommand()
    {
        $cn = '\\Dolphin\\Commands\\'.ucfirst(strtolower($this->commandName));

        if (!class_exists($cn)) {
            $this->console->stop('Unknown command: '.'<'.$this->commandName.'>', Console::RED);
        }

        $command = new $cn($this->container);

        if (!$command instanceof CommandInterface) {
            $this->console->stop("$cn not instance of CommandInterface", Console::RED);
        }

        if (!method_exists($command, $this->commandAction)) {
            $this->console->stop(
                "Invalid arguments: unknown method [{$this->commandAction}] of {$this->commandName}",
                Console::RED
            );
        }

        call_user_func([$command, $this->commandAction]);
    }
}
