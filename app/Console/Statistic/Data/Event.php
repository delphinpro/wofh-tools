<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Data;

use Carbon\CarbonInterface;

class Event extends Entry
{
    public function __construct(int $eventId, CarbonInterface $stateAt, array $data)
    {
        $this->data = [
            'state_at'        => $stateAt,
            'id'              => $eventId,
            'town_id'         => $data['town_id'] ?? null,
            'account_id'      => $data['account_id'] ?? null,
            'country_id'      => $data['country_id'] ?? null,
            'country_id_from' => $data['country_id_from'] ?? null,
            'role'            => $data['role'] ?? null,
            'props'           => json_encode($data['props']),
        ];
    }
}
