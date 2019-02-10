<?php

namespace Dolphin;


/**
 * Interface CommandInterface
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016 delphinpro
 * @license     Licensed under the MIT license
 * @package     Dolphin
 */
interface CommandInterface
{
    public static function getDescription();


    public function help();
}
