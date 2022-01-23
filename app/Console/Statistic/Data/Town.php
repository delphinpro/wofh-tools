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
use function Helpers\Wofh\wonderId;
use function Helpers\Wofh\wonderLevel;

/**
 * Class Town
 * @property int $id
 * @property string $name
 * @property int $account_id
 * @property int $country_id
 * @property int $pop
 * @property int $wonder
 * @property bool $lost
 * @property bool $destroyed
 * @property array $names
 */
class Town extends Entry
{
    const KEY_NAME       = 0;
    const KEY_ACCOUNT_ID = 1;
    const KEY_POP        = 2;
    const KEY_WONDER     = 3;

    public static function createFromFile(int $id, array $townArray): Town
    {
        $wonder = array_key_exists(Town::KEY_WONDER, $townArray) ? $townArray[Town::KEY_WONDER] : null;
        return new Town([
            'id'         => $id,
            'name'       => $townArray[Town::KEY_NAME],
            'account_id' => $townArray[Town::KEY_ACCOUNT_ID] ?: null,
            'country_id' => null,
            'pop'        => $townArray[Town::KEY_POP],
            'wonder'     => $wonder ?: null,
            'lost'       => !$townArray[Town::KEY_ACCOUNT_ID],
            'destroyed'  => false,
            'names'      => null,
        ]);
    }

    public static function createFromDb(object $townObject): Town
    {
        return new Town((array)$townObject);
    }

    protected function __construct(array $town)
    {
        $town['names'] = DB::raw("JSON_MERGE_PATCH(`names`, JSON_OBJECT())");
        $this->data = $town;
    }

    public function setNames(QueryExpression $value): Town
    {
        $this->data['names'] = $value;
        return $this;
    }

    public function wonderId(): int { return wonderId($this->wonder); }

    public function wonderLevel(): int { return wonderLevel($this->wonder); }

    public function isBarbarian(): bool { return $this->lost; }

    public function isNotBarbarian(): bool { return !$this->isBarbarian(); }

    public function wonderExists(): bool { return !!$this->wonder; }

    public function wonderNotExists(): bool { return !$this->wonder; }

    public function wonderActivated(): bool { return $this->wonder > 20999; }

    public function wonderNotActivated(): bool { return $this->wonder < 21000; }
}
