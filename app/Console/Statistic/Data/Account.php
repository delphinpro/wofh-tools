<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020–2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Data;

use Illuminate\Database\Query\Expression as QueryExpression;
use Illuminate\Support\Facades\DB;

/**
 * Class Account
 * @property int $id
 * @property string $name
 * @property int $race
 * @property int $sex
 * @property int $country_id
 * @property float $attack
 * @property float $defense
 * @property float $science
 * @property float $production
 * @property int $role
 * @property int $active
 * @property int $pop
 * @property int $towns
 * @property array $names
 * @property array $countries
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

    private int $deltaPop = 0;
    private int $deltaTowns = 0;
    private float $deltaScience = 0;
    private float $deltaProduction = 0;
    private float $deltaAttack = 0;
    private float $deltaDefence = 0;
    private bool $hiddenRating;

    public static function createFromFile(int $id, array $account): Account
    {
        return new Account([
            'id'         => $id,
            'race'       => $account[Account::KEY_RACE],
            'sex'        => $account[Account::KEY_SEX],
            'country_id' => $account[Account::KEY_COUNTRY_ID] ?: null,
            'role'       => $account[Account::KEY_ROLE],
            'attack'     => $account[Account::KEY_RATING_ATTACK],
            'defense'    => $account[Account::KEY_RATING_DEFENSE],
            'science'    => $account[Account::KEY_RATING_SCIENCE],
            'production' => $account[Account::KEY_RATING_PRODUCTION],
            'name'       => $account[Account::KEY_NAME],
            'active'     => true,
            'pop'        => 0,
            'towns'      => 0,
        ]);
    }

    public static function createFromDb(object $accountObject): Account
    {
        return new Account((array)$accountObject);
    }

    public function __construct(array $account)
    {
        $account['names'] = DB::raw("JSON_MERGE_PATCH(`names`, JSON_OBJECT())");
        $account['countries'] = DB::raw("JSON_MERGE_PATCH(`countries`, JSON_OBJECT())");
        $this->data = $account;
        $this->hiddenRating = ($this->science + $this->production + $this->production + $this->defense) == 0;
    }

    // @formatter:off
    public function setNames(QueryExpression $value): Account { $this->data['names'] = $value; return $this; }
    public function setCountries(QueryExpression $value): Account { $this->data['countries'] = $value; return $this; }
    public function getDeltaPop(): int { return $this->deltaPop; }
    public function setDeltaPop(int $delta): Account { $this->deltaPop = $delta; return $this; }
    public function getDeltaTowns(): int { return $this->deltaTowns; }
    public function setDeltaTowns(int $delta): Account { $this->deltaTowns = $delta; return $this; }
    public function getDeltaScience(): float { return $this->deltaScience; }
    public function setDeltaScience(float $delta): Account { $this->deltaScience = $delta; return $this; }
    public function getDeltaProduction(): float { return $this->deltaProduction; }
    public function setDeltaProduction(float $delta): Account { $this->deltaProduction = $delta; return $this; }
    public function getDeltaAttack(): float { return $this->deltaAttack; }
    public function setDeltaAttack(float $delta): Account { $this->deltaAttack = $delta; return $this; }
    public function getDeltaDefence(): float { return $this->deltaDefence; }
    public function setDeltaDefence(float $delta): Account { $this->deltaDefence = $delta; return $this; }
    public function isActive(): bool { return !!$this->active; }
    public function inCountry(): bool { return !!$this->country_id; }
    public function isHiddenRating(): bool { return $this->hiddenRating; }
    public function isShownRating(): bool { return !$this->hiddenRating; }
    // @formatter:on
}
