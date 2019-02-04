<?php

namespace Dolphin;


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
class Help extends Cli
{
    private const INDENT_COMMAND = 2;
    private const INDENT_OPTIONS = 4;

    private $strings;


    public function __construct()
    {
        $this->strings = [];
    }


    public function addString(string $string): void
    {
        $this->strings[] = [
            'text' => $string,
            'attr' => null,
            'eol'  => true,
        ];
    }


    public function addCommand(
        string $cmd,
        string $desc,
        string $options = '',
        string $required = ''
    ): void {
        $this->strings[] = [
            'text' => str_pad('', self::INDENT_COMMAND, ' ').$cmd,
            'attr' => Cli::FONT_CYAN,
            'eol'  => false,
        ];
        $this->strings[] = [
            'text' => ' '.$desc,
            'attr' => null,
            'eol'  => true,
        ];
        if ($required) {
            $this->strings[] = [
                'text' => str_pad('', self::INDENT_OPTIONS, ' ').$required,
                'attr' => Cli::FONT_GREEN,
                'eol'  => empty($options),
            ];
        }
        if ($options) {
            $this->strings[] = [
                'text' => str_pad('', self::INDENT_OPTIONS, ' ').$options,
                'attr' => Cli::FONT_YELLOW,
                'eol'  => true,
            ];
        }
    }


    public function addOption(string $opt, string $desc): void
    {
        $this->strings[] = [
            'text' => str_pad('', self::INDENT_OPTIONS, ' ').$opt,
            'attr' => Cli::FONT_YELLOW,
            'eol'  => false,
        ];
        $this->strings[] = [
            'text' => ' '.$desc,
            'attr' => null,
            'eol'  => true,
        ];
    }


    public function display(): void
    {
        foreach ($this->strings as $string) {
            $this->write($string['text'], $string['attr'], $string['eol']);
        }
    }
}
