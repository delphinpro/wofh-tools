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
 * Class Account
 * @property int $id
 * @property string $name
 * @property int $race
 * @property int $sex
 * @property int $country_id
 * @property float $rating_attack
 * @property float $rating_defense
 * @property float $rating_science
 * @property float $rating_production
 * @property int $role
 * @property int $pop
 * @property int $towns
 */
class Account extends Entry
{
    const KEY_NAME              = 0;
    const KEY_RACE              = 1;
    const KEY_SEX               = 2;
    const KEY_COUNTRY_ID        = 3;
    const KEY_RATING_ATTACK     = 4;
    const KEY_RATING_DEFENSE    = 5;
    const KEY_RATING_SCIENCE    = 6;
    const KEY_RATING_PRODUCTION = 7;
    const KEY_ROLE              = 8;

    public function __construct(int $id, array $account)
    {
        $this->data = [
            'id'                => $id,
            'name'              => $account[Account::KEY_NAME],
            'race'              => $account[Account::KEY_RACE],
            'sex'               => $account[Account::KEY_SEX],
            'country_id'        => $account[Account::KEY_COUNTRY_ID],
            'rating_attack'     => $account[Account::KEY_RATING_ATTACK],
            'rating_defense'    => $account[Account::KEY_RATING_DEFENSE],
            'rating_science'    => $account[Account::KEY_RATING_SCIENCE],
            'rating_production' => $account[Account::KEY_RATING_PRODUCTION],
            'role'              => $account[Account::KEY_ROLE],
            'pop'               => 0,
            'towns'             => 0,
        ];
    }

    public function inCountry(): bool { return !!$this->country_id; }

    public function rating(): float
    {
        return $this->rating_science
            + $this->rating_production
            + $this->rating_production
            + $this->rating_defense;
    }
}
