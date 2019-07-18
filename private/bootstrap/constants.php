<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

define('DIR_ROOT', realpath(__DIR__.'/../../'));
define('DIR_CACHE', DIR_ROOT.DIRECTORY_SEPARATOR.'.cache');
define('DIR_LOGS', DIR_ROOT.DIRECTORY_SEPARATOR.'.logs');
define('DIR_TMP', DIR_ROOT.DIRECTORY_SEPARATOR.'.tmp');
define('DIR_CONFIG', DIR_ROOT.DIRECTORY_SEPARATOR.'config');
define('DIR_TWIG_TEMPLATES', DIR_ROOT.DIRECTORY_SEPARATOR.'private'.DIRECTORY_SEPARATOR.'templates');

define('STD_DATETIME', 'Y-m-d H:i:s');
define('STD_DATE_H', 'Y-m-d__H-00-00');
define('DATE_FILE', 'Y-m-d_H');
