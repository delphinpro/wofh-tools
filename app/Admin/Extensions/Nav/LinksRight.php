<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Admin\Extensions\Nav;


/**
 * Class Links
 *
 * @package App\Admin\EXtensions\Nav
 */
class LinksRight
{
    public function __toString()
    {
        return <<<HTML
<li>
    <a href="/" target="_blank">
      <i class="fa fa-desktop"></i>
    </a>
</li>
HTML;
    }
}
