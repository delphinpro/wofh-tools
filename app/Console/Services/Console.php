<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Services;


use Illuminate\Console\OutputStyle;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;


/**
 * Class Console
 *
 * @package App\Services
 */
class Console
{
    const COLOR_DEFAULT = 0;
    const BOLD          = 1;
    const UNDERLINE     = 4;
    const BLACK         = 30;
    const RED           = 31;
    const GREEN         = 32;
    const YELLOW        = 33;
    const BLUE          = 34;
    const MAGENTA       = 35;
    const CYAN          = 36;
    const WHITE         = 37;

    const BG_BLACK   = 40;
    const BG_RED     = 41;
    const BG_GREEN   = 42;
    const BG_YELLOW  = 43;
    const BG_BLUE    = 44;
    const BG_MAGENTA = 45;
    const BG_CYAN    = 46;
    const BG_WHITE   = 47;

    /** @var \Illuminate\Console\OutputStyle */
    private $output;


    public function __construct()
    {
        $input = new ArrayInput([]);
        $output = new ConsoleOutput();
        $this->output = new OutputStyle($input, $output);
    }


    public function line($string, $style = null)
    {
        $styled = $style ? "<$style>$string</$style>" : $string;
        $this->output->writeln($styled);
    }


    public function info($string)
    {
        $this->line($string, 'info');
    }


    public function error($string)
    {
        $this->line($string, 'error');
    }


    public function warn($string)
    {
        if (!$this->output->getFormatter()->hasStyle('warning')) {
            $style = new OutputFormatterStyle('yellow');

            $this->output->getFormatter()->setStyle('warning', $style);
        }

        $this->line($string, 'warning');
    }


    public function section(string $message)
    {
        $this->output->section($message);
    }


    public function trimPath($path)
    {
        return '~'.str_replace(
                '\\',
                '/',
                str_replace(base_path(), '', $path)
            );
    }
}
