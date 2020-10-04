<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App;


use App\Traits\ModelAttributesToCamelCaseArray;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin IdeHelperStatCommon
 */
class StatCommon extends Model
{
    use ModelAttributesToCamelCaseArray;


    public $table = 'common';

    protected $dates = [
        'state_at',
    ];

    public function toArray()
    {
        return $this->attributesToCamelCaseArray();
    }
}
