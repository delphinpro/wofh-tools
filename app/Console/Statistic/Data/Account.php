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
 *
 * @package App\Console\Services\Statistic
 * @property int $id
 * @property string $name
 * @property int $race
 * @property int $sex
 * @property int $countryId
 * @property float $ratingAttack
 * @property float $ratingDefense
 * @property float $ratingScience
 * @property float $ratingProduction
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
            'id'               => $id,
            'name'             => $account[Account::KEY_NAME],
            'race'             => $account[Account::KEY_RACE],
            'sex'              => $account[Account::KEY_SEX],
            'countryId'        => $account[Account::KEY_COUNTRY_ID],
            'ratingAttack'     => $account[Account::KEY_RATING_ATTACK],
            'ratingDefense'    => $account[Account::KEY_RATING_DEFENSE],
            'ratingScience'    => $account[Account::KEY_RATING_SCIENCE],
            'ratingProduction' => $account[Account::KEY_RATING_PRODUCTION],
            'role'             => $account[Account::KEY_ROLE],
            'pop'              => 0,
            'towns'            => 0,
        ];
    }
}
