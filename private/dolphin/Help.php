<?php

namespace Dolphin;


use Psr\Container\ContainerInterface;


/**
 * Class Help
 * Dolphin command line interface. Print help of commands.
 *
 * @package     Dolphin
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2019 delphinpro
 * @license     Licensed under the MIT license
 * @package     Dolphin
 */
class Help
{
    private const INDENT_COMMAND = 2;
    private const INDENT_OPTIONS = 2;
    private const MAX_STRING     = 70;

    /** @var array */
    private $commands;

    /** @var array */
    private $options;

    /** @var Console */
    private $console;

    private $cmdNameMaxLength;

    private $optNameMaxLength;


    public function __construct(ContainerInterface $container)
    {
        $this->commands = [];
        $this->options = [];
        $this->cmdNameMaxLength = 0;
        $this->optNameMaxLength = 0;
        $this->console = $container['console'];
    }


    public function addCommand(string $cmd, string $desc, array $options = []): void
    {
        $cmd = str_pad('', Help::INDENT_COMMAND, ' ', STR_PAD_LEFT).$cmd;

        ksort($options);

        $this->commands[] = [
            'name' => $cmd,
            'desc' => $desc,
            'opts' => $options,
        ];

        $this->cmdNameMaxLength = max($this->cmdNameMaxLength, strlen($cmd) + 1);
    }


    public function addOption(string $opt, string $desc): void
    {
        $opt = str_pad('', Help::INDENT_OPTIONS, ' ', STR_PAD_LEFT).$opt;

        $this->options[] = [
            'name' => $opt,
            'desc' => $desc,
        ];

        $this->optNameMaxLength = max($this->optNameMaxLength, strlen($opt) + 1);
    }


    public function display($message = ''): void
    {
        if ($message) {
            $this->console->write($message);
        }

        $this->console->write('Commands:');

        foreach ($this->commands as $cmd) {
            $name = str_pad($cmd['name'], $this->cmdNameMaxLength);
            $this->console->write($name, Console::CYAN, false);
            $this->console->write($cmd['desc']);
            if (!empty($cmd['opts'])) {
                $this->console->write(str_pad('', $this->cmdNameMaxLength + 1), false);
                $counter = 0;
                foreach ($cmd['opts'] as $opt => $attr) {
                    $required = array_key_exists('required', $attr) && $attr['required'];
                    if ($counter++ < count($cmd['opts']) - 1) {
                        $opt .= ', ';
                    }
                    $this->console->write($opt, $required ? Console::RED : Console::YELLOW, false);
                }
                $this->console->write('');
            }
        }

        $this->console->write('Options:');

        $maxString = Help::MAX_STRING - $this->optNameMaxLength;

        foreach ($this->options as $option) {
            $name = str_pad($option['name'], $this->optNameMaxLength);
            $this->console->write($name, Console::YELLOW, false);
            $strings = explode("\n", $option['desc']);
            foreach ($strings as &$string) {
                $string = trim($string);
                if (mb_strlen($string) > $maxString) {
                    $words = explode(' ', $string);
                    $result = [];
                    $line = '';
                    foreach ($words as $word) {
                        $new = ($line ? $line.' ' : $line).$word;
                        if (mb_strlen($new) <= $maxString) {
                            $line = $new;
                        } else {
                            $result[] = str_pad('', $this->optNameMaxLength + 1).$line;
                            $line = $word;
                        }
                    }
                    $string = join("\n", $result);
                }
            }
            $this->console->write(join("\n", $strings));
        }
    }
}
