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
use Illuminate\Support\Str;
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

    public function line($string = '', $codes = null)
    {
        $this->output->writeln($this->makeString($string, $codes));
    }

    public function info($string)
    {
        $this->line($string, self::BLUE);
    }

    public function success($string)
    {
        $this->line($string, self::GREEN);
    }

    public function warn($string)
    {
        $this->line($string, self::YELLOW);
    }

    public function error($string)
    {
        $this->line($string, Console::RED);
    }

    public function alert($string)
    {
        $length = Str::length(strip_tags($string)) + 12;

        $this->line(str_repeat('*', $length), self::CYAN);
        $this->line('*     '.$string.'     *', self::CYAN);
        $this->line(str_repeat('*', $length), self::CYAN);
    }

    public function title(string $string)
    {
        $this->line();
        $this->info($string);
        $this->info(str_repeat('=', Str::length($string)));
    }

    public function section(string $string)
    {
        $this->line();
        $this->line($string, self::MAGENTA);
        $this->line(str_repeat('-', Str::length($string)), self::MAGENTA);
    }

    public function stackTrace(\Throwable $e)
    {
        foreach ($e->getTrace() as $index => $item) {
            $call = '';
            if (array_key_exists('file', $item)) {
                $file = $this->trimPath($item['file']);
                if (Str::startsWith($file, '~/vendor')) break;
                $call .= $file.':'.$item['line'].'  ';
            }
            if (array_key_exists('class', $item)) $call .= $item['class'];
            if (array_key_exists('type', $item)) $call .= $item['type'];
            if (array_key_exists('function', $item)) $call .= $item['function'].'()';
            if ($call) $this->error('[ '.$index.' ] '.$call);
        }
    }

    public function makeString($string, $codes = null)
    {
        if (is_int($codes)) {
            $codes = (array)$codes;
        }

        if (is_array($codes)) {
            return "\033[".join(';', $codes).'m'.$string."\033[0m";
        }

        return $string;
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
