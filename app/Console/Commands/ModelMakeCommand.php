<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2021 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Commands;


use Illuminate\Foundation\Console\ModelMakeCommand as Command;


class ModelMakeCommand extends Command
{
    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return "{$rootNamespace}\Models";
    }
}
