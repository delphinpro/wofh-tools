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
 * Class Country
 * @property int $id
 * @property string $name
 * @property string $flag
 * @property array $diplomacy
 * @property int $pop
 * @property int $accounts
 * @property int $towns
 * @property float $ratingAttack
 * @property float $ratingDefense
 * @property float $ratingScience
 * @property float $ratingProduction
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
            'id'               => $id,
            'name'             => $country[Country::KEY_NAME],
            'flag'             => $country[Country::KEY_FLAG],
            'diplomacy'        => $diplomacy,
            'pop'              => 0,
            'accounts'         => 0,
            'towns'            => 0,
            'ratingAttack'     => 0,
            'ratingDefense'    => 0,
            'ratingScience'    => 0,
            'ratingProduction' => 0,
        ];
    }
}
