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
 * Class Country
 * @property int $id
 * @property string $name
 * @property string $flag
 * @property array $diplomacy
 * @property int $pop
 * @property int $accounts
 * @property int $towns
 * @property float $attack
 * @property float $defense
 * @property float $science
 * @property float $production
 * @property bool $active
 */
class Country extends Entry
{
    const KEY_NAME      = 0;
    const KEY_FLAG      = 1;
    const KEY_DIPLOMACY = 2;

    private int $deltaPop = 0;
    private int $deltaAccounts = 0;
    private int $deltaTowns = 0;
    private float $deltaScience = 0;
    private float $deltaProduction = 0;
    private float $deltaAttack = 0;
    private float $deltaDefence = 0;

    public static function createFromFile(int $id, array $country): Country
    {
        $diplomacy = array_key_exists(Country::KEY_DIPLOMACY, $country)
            ? $country[Country::KEY_DIPLOMACY]
            : [];

        return new Country([
            'id'         => $id,
            'name'       => $country[Country::KEY_NAME],
            'flag'       => $country[Country::KEY_FLAG],
            'diplomacy'  => json_encode($diplomacy),
            'active'     => true,
            'pop'        => 0,
            'accounts'   => 0,
            'towns'      => 0,
            'attack'     => 0,
            'defense'    => 0,
            'science'    => 0,
            'production' => 0,
        ]);
    }

    public static function createFromDb(object $countryObject): Country
    {
        return new Country((array)$countryObject);
    }

    public function __construct(array $country)
    {
        $country['names'] = DB::raw("JSON_MERGE_PATCH(`names`, JSON_OBJECT())");
        $country['flags'] = DB::raw("JSON_MERGE_PATCH(`flags`, JSON_OBJECT())");
        $this->data = $country;
    }

    // @formatter:off
    public function setNames(QueryExpression $value): Country { $this->data['names'] = $value; return $this; }
    public function setFlags(QueryExpression $value): Country { $this->data['flags'] = $value; return $this; }
    public function getDeltaPop(): int { return $this->deltaPop; }
    public function setDeltaPop(int $delta): Country { $this->deltaPop = $delta; return $this; }
    public function getDeltaAccounts(): int { return $this->deltaPop; }
    public function setDeltaAccounts(int $delta): Country { $this->deltaPop = $delta; return $this; }
    public function getDeltaTowns(): int { return $this->deltaTowns; }
    public function setDeltaTowns(int $delta): Country { $this->deltaTowns = $delta; return $this; }
    public function getDeltaScience(): float { return $this->deltaScience; }
    public function setDeltaScience(float $delta): Country { $this->deltaScience = $delta; return $this; }
    public function getDeltaProduction(): float { return $this->deltaProduction; }
    public function setDeltaProduction(float $delta): Country { $this->deltaProduction = $delta; return $this; }
    public function getDeltaAttack(): float { return $this->deltaAttack; }
    public function setDeltaAttack(float $delta): Country { $this->deltaAttack = $delta; return $this; }
    public function getDeltaDefence(): float { return $this->deltaDefence; }
    public function setDeltaDefence(float $delta): Country { $this->deltaDefence = $delta; return $this; }
    public function isActive(): bool { return !!$this->active; }
    // @formatter:on
}
