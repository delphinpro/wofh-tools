<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Data;

use Carbon\CarbonInterface;

/**
 * Class Town
 * @property int $id
 * @property string $name
 * @property int $account_id
 * @property int $country_id
 * @property int $pop
 * @property int $wonder
 * @property bool $lost
 * @property bool $destroy
 * @property array $names
 */
class Town extends Entry
{
    const KEY_NAME       = 0;
    const KEY_ACCOUNT_ID = 1;
    const KEY_POP        = 2;
    const KEY_WONDER     = 3;

    public function __construct(int $id, array $town, ?CarbonInterface $time = null)
    {
        $wonder = array_key_exists(Town::KEY_WONDER, $town) ? $town[Town::KEY_WONDER] : null;

        $this->data = [
            'id'         => $id,
            'name'       => $town[Town::KEY_NAME],
            'account_id' => $town[Town::KEY_ACCOUNT_ID] ?: null,
            'country_id' => null,
            'pop'        => $town[Town::KEY_POP],
            'wonder'     => $wonder,
            'lost'       => false,
            'destroy'    => false,
        ];
    }

    public function wonderId(): int { return $this->wonder % 1000; }

    public function wonderLevel(): int { return (int)floor($this->wonder / 1000); }

    public function isNullPopulation(): bool
    {
        return $this->pop == 0;
    }

    public function isBarbarian(): bool
    {
        return $this->account_id == 0;
    }
}
