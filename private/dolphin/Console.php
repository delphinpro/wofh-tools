<?php

namespace Dolphin;


/**
 * Class Console
 * Dolphin command line interface
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 * @package     Dolphin
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

    const LINE_WIDTH = 70;

    private $colorEnabled = true;


    public function write($message, $codes = null, $eol = true)
    {
        if ($codes === false) {
            $codes = null;
            $eol = false;
        }

        echo $this->coloredOutput($message, $codes);

        if ($eol) {
            echo PHP_EOL;
        }
    }


    public function writeFixedWidth($message, $length, $symbol = '.', $codes = null)
    {
        $message = str_pad($message.' ', $length, $symbol).' ';
        $this->write($message, $codes, false);
    }


    public function error($message, $eol = true)
    {
        echo $this->coloredOutput($message, Console::RED);

        if ($eol) {
            echo PHP_EOL;
        }
    }


    public function line($symbol, $length, $codes = null, $eol = true)
    {
        $message = str_pad('', $length, $symbol);
        $this->write($message, $codes, $eol);
    }


    public function lineSingle($length = Console::LINE_WIDTH, $codes = null, $eol = true)
    {
        $this->line('-', $length, $codes, $eol);
    }


    public function lineDouble($length = Console::LINE_WIDTH, $codes = null, $eol = true)
    {
        $this->line('=', $length, $codes, $eol);
    }


    public function stop($message = '', $codes = null)
    {
        if ($message) {
            $this->write($message, $codes, true);
        }

        die;
    }


    //== ====================================================================================== ==//
    //== Private methods
    //== ====================================================================================== ==//


    private function coloredOutput($message, $codes)
    {
        if ($this->colorEnabled) {
            if (is_int($codes)) {
                $codes = (array)$codes;
            }

            if (is_array($codes)) {
                return "\033[".join(';', $codes).'m'.$message."\033[0m";
            }
        }

        return $message;
    }
}
