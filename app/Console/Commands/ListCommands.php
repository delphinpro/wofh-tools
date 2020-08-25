<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Commands;


use App\Console\Color;
use App\Console\Traits\ConsoleColors;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;


trait ListCommands
{
    use ConsoleColors;


    /**
     * List all make commands.
     *
     * @param string $commandGroup
     *
     * @return void
     */
    protected function allCommands(string $commandGroup)
    {
        $commands = collect(Artisan::all())->mapWithKeys(function ($command, $key) use ($commandGroup) {
            if (Str::startsWith($key, $commandGroup.':')) {
                return [$key => $command];
            }

            return [];
        })->toArray();

        $width = $this->getColumnWidth($commands);

        /** @var Command $command */
        foreach ($commands as $command) {
            $name = $this->makeString(str_pad($command->getName(), $width), Color::GREEN);
            $this->line(' '.$name.$command->getDescription());
        }
    }


    /**
     * @param Command[]|string[] $commands
     *
     * @return int
     */
    protected function getColumnWidth(array $commands)
    {
        $widths = [];

        foreach ($commands as $command) {
            $widths[] = $this->strlen($command->getName());
            foreach ($command->getAliases() as $alias) {
                $widths[] = $this->strlen($alias);
            }
        }

        return $widths ? max($widths) + 2 : 0;
    }


    /**
     * Returns the length of a string, using mb_strwidth if it is available.
     *
     * @param string $string The string to check its length
     *
     * @return int The length of the string
     */
    protected function strlen($string)
    {
        if (false === $encoding = mb_detect_encoding($string, null, true)) {
            return strlen($string);
        }

        return mb_strwidth($string, $encoding);
    }
}
