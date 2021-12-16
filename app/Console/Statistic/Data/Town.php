<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Data;

/**
 * Class Town
 *
 * @package App\Console\Services\Statistic
 * @property int $id
 * @property string $name
 * @property int $accountId
 * @property int $pop
 * @property int $wonder
 * @property int $wonderId
 * @property int $wonderLevel
 * @property int $countryId
 */
class Town extends Entry
{
    const KEY_NAME       = 0;
    const KEY_ACCOUNT_ID = 1;
    const KEY_POP        = 2;
    const KEY_WONDER     = 3;

    public function __construct(int $id, array $town)
    {
        $wonder = array_key_exists(Town::KEY_WONDER, $town) ? $town[Town::KEY_WONDER] : 0;

        $this->data = [
            'id'          => $id,
            'name'        => $town[Town::KEY_NAME],
            'accountId'   => $town[Town::KEY_ACCOUNT_ID],
            'pop'         => $town[Town::KEY_POP],
            'wonder'      => $wonder,
            'wonderId'    => $wonder % 1000,
            'wonderLevel' => (int)floor($wonder / 1000),
            'countryId'   => 0,
        ];
    }

    public function isNullPopulation(): bool
    {
        return $this->pop == 0;
    }

    public function isBarbarian(): bool
    {
        return $this->accountId == 0;
    }
}
