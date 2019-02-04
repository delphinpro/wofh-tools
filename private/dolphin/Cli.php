<?php

namespace Dolphin;


/**
 * Dolphin command line interface
 * Class Cli
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016 delphinpro
 * @license     Licensed under the MIT license
 * @package     Dolphin
 */
class Cli
{
    const FONT_DEFAULT   = 0;
    const FONT_BOLD      = 1;
    const FONT_UNDERLINE = 4;
    const FONT_BLACK     = 30;
    const FONT_RED       = 31;
    const FONT_GREEN     = 32;
    const FONT_YELLOW    = 33;
    const FONT_BLUE      = 34;
    const FONT_MAGENTA   = 35;
    const FONT_CYAN      = 36;
    const FONT_WHITE     = 37;
    const BG_BLACK       = 40;
    const BG_RED         = 41;
    const BG_GREEN       = 42;
    const BG_YELLOW      = 43;
    const BG_BLUE        = 44;
    const BG_MAGENTA     = 45;
    const BG_CYAN        = 46;
    const BG_WHITE       = 47;

    protected $colorEnabled = true;


    protected function write($message, $codes = null, $eol = false)
    {
        echo $this->color($message, $codes);

        if ($eol) {
            echo PHP_EOL;
        }
    }


    protected function writeLn($message, $codes = null)
    {
        $this->write($message, $codes, true);
    }


    protected function halt($message, $codes = null)
    {
        $this->writeLn($message, $codes);
        $this->stop();
    }


    protected function stop()
    {
        exit;
    }


    protected function color($message, $codes)
    {
        if ($this->colorEnabled) {
            if (is_int($codes)) {
                $codes = (array)$codes;
            }

            if (is_array($codes)) {
                return "\033[".join(';', $codes).'m'.$message."\033[0m ";
            }
        }

        return $message;
    }
}
