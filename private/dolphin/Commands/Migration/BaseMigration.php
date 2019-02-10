<?php

namespace Dolphin\Commands\Migration;


use Dolphin\Console;
use Illuminate\Database\Capsule\Manager as Capsule;


/**
 * Class BaseMigration
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016 delphinpro
 * @license     Licensed under the MIT license
 * @package     Dolphin\Commands\Migration
 */
class BaseMigration
{
    /** @var \Illuminate\Database\Connection */
    protected $db;

    /** @var \Illuminate\Database\Schema\Builder */
    protected $schema;

    /** @var Console */
    protected $console;


    public function __construct()
    {
        $this->db = Capsule::connection();
        $this->schema = Capsule::schema();
        $this->console = new Console();
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
