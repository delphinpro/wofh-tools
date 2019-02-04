<?php

namespace Dolphin\Commands\Migration;


use Dolphin\Cli;
use Illuminate\Database\Capsule\Manager as Capsule;


/**
 * Class BaseMigration
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016 delphinpro
 * @license     Licensed under the MIT license
 * @package     Dolphin\Commands\Migration
 */
class BaseMigration extends Cli
{
    /** @var \Illuminate\Database\Connection */
    protected $db;

    /** @var \Illuminate\Database\Schema\Builder */
    protected $schema;


    public function __construct()
    {
        $this->db = Capsule::connection();
        $this->schema = Capsule::schema();
    }


    public function up()
    {
    }


    public function down()
    {
    }


    public function description()
    {
        return '';
    }
}
