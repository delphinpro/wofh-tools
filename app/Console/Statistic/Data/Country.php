<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020–2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Data;

/**
 * Class Country
 * @property int $id
 * @property string $name
 * @property string $flag
 * @property array $diplomacy
 * @property int $pop
 * @property int $accounts
 * @property int $towns
 * @property float $rating_attack
 * @property float $rating_defense
 * @property float $rating_science
 * @property float $rating_production
 */
class Country extends Entry
{
    const KEY_NAME      = 0;
    const KEY_FLAG      = 1;
    const KEY_DIPLOMACY = 2;

    public function __construct(int $id, array $country)
    {
        $diplomacy = array_key_exists(Country::KEY_DIPLOMACY, $country)
            ? $country[Country::KEY_DIPLOMACY]
            : [];

        $this->data = [
            'id'                => $id,
            'name'              => $country[Country::KEY_NAME],
            'flag'              => $country[Country::KEY_FLAG],
            'diplomacy'         => $diplomacy,
            'pop'               => 0,
            'accounts'          => 0,
            'towns'             => 0,
            'rating_attack'     => 0,
            'rating_defense'    => 0,
            'rating_science'    => 0,
            'rating_production' => 0,
        ];
    }
}
