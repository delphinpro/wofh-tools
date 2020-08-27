<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */


namespace App\Traits;


use App\Console\Color;
use Illuminate\Support\Str;


trait CliColors
{
    protected $colorEnabled = true;


    /**
     * Write a string in an alert box.
     *
     * @param string $string
     *
     * @return void
     */
    public function alert($string)
    {
        $length = Str::length(strip_tags($string)) + 12;

        $this->coloredLine(str_repeat('*', $length), Color::CYAN);
        $this->coloredLine('*     '.$string.'     *', Color::CYAN);
        $this->coloredLine(str_repeat('*', $length), Color::CYAN);

        $this->output->newLine();
    }


    /**
     * @param      $string
     * @param null $verbosity
     *
     * @noinspection PhpUnusedParameterInspection
     */
    public function error($string, $verbosity = null)
    {
        $this->coloredLine($string, Color::RED);
    }


    public function coloredLine($message, $codes)
    {
        $this->line($this->makeString($message, $codes));
    }


    protected function makeString($string, $codes = null)
    {
        if ($this->colorEnabled) {
            if (is_int($codes)) {
                $codes = (array)$codes;
            }

            if (is_array($codes)) {
                return "\033[".join(';', $codes).'m'.$string."\033[0m";
            }
        }

        return $string;
    }


    protected function trimPath($path)
    {
        return '~'.str_replace(
                '\\',
                '/',
                str_replace(base_path(), '', $path)
            );
    }
}
