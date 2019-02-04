<?php

namespace Dolphin;


/**
 * Class BaseCommand
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016 delphinpro
 * @license     Licensed under the MIT license
 * @package     Dolphin
 */
abstract class BaseCommand extends Cli
{
    protected $name;

    protected $action;

    protected $arguments;

    protected $aliases = [];


    final public function execute($arguments)
    {
        if (!is_array($arguments)) {
            $this->halt('Invalid arguments', Cli::FONT_RED);
        }

        $tmp = array_shift($arguments);
        $tmp = explode(':', $tmp);

        $this->name = $tmp[0];
        $this->action = isset($tmp[1]) ? $tmp[1] : '';

        if (empty($this->action)) {
            $this->action = 'help';
        }

        if (!method_exists($this, $this->action)) {
            $this->halt(
                "Invalid arguments: unknown method [{$this->action}] of {$this->name}",
                Cli::FONT_RED
            );
        }

        $this->arguments = $this->parseArguments($arguments);

        call_user_func([$this, $this->action]);
    }


    abstract public function getDescription();


    final protected function parseArguments($args)
    {
        $result = [];
        foreach ($args as $arg) {
            if (strpos($arg, '=') === false) {
                $arg .= '=true';
            }

            list($key, $value) = explode('=', $arg);

            if (array_key_exists($key, $this->aliases)) {
                $key = $this->aliases[$key];
            }

            $key = ltrim($key, '-');

            if (is_numeric($value)) {
                $value = (int)$value;
            }

            if ($value === 'true') {
                $value = true;
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
