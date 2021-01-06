<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Updater;


use App\Models\World;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;


/**
 * Class UpdateChecker
 *
 * @package App\Console\Traits
 */
trait Checker
{
    /** @var string|null */
    private $savedPrefix;

    protected function checkTables(World $world)
    {
        $this->setWorldPrefix($world->sign);

        if (!\Schema::hasTable('towns')) {
            \Schema::create('towns', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->integer('account_id')->unsigned();
                $table->tinyInteger('lost')->unsigned()->default(0);
                $table->tinyInteger('destroy')->unsigned()->default(0);
                $table->json('extra')->nullable()->default(null);
            });
        }

        if (!\Schema::hasTable('towns_stat')) {
            \Schema::create('towns_stat', function (Blueprint $table) {
                $table->timestamp('state_at');
                $table->integer('id')->unsigned();
                $table->mediumInteger('pop')->unsigned();
                $table->smallInteger('wonder_id')->unsigned()->default(0);
                $table->tinyInteger('wonder_level')->unsigned()->default(0);
                // $table->smallInteger('delta_pop')->unsigned()->nullable()->default(null);

                $table->primary(['state_at', 'id']);
                $table->index('pop');
            });
        }

        if (!\Schema::hasTable('accounts')) {
            \Schema::create('accounts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->tinyInteger('race')->unsigned()->default(0);
                $table->tinyInteger('sex')->unsigned()->default(0);
                $table->integer('country_id')->unsigned()->default(0);
                $table->tinyInteger('role')->unsigned()->default(0);
                $table->tinyInteger('active')->unsigned()->default(1);
                $table->json('extra')->nullable()->default(null);

                $table->index('country_id');
                $table->index('active');
            });
        }

        if (!\Schema::hasTable('accounts_stat')) {
            \Schema::create('accounts_stat', function (Blueprint $table) {
                $table->timestamp('state_at');
                $table->integer('id')->unsigned();
                $table->integer('country_id')->unsigned();
                $table->tinyInteger('role')->unsigned();
                $table->integer('pop')->unsigned();
                $table->smallInteger('towns')->unsigned();
                $table->float('science');
                $table->float('production');
                $table->float('attack');
                $table->float('defense');
                // $table->integer('delta_pop');
                // $table->smallInteger('delta_towns');
                // $table->float('delta_science');
                // $table->float('delta_production');
                // $table->float('delta_attack');
                // $table->float('delta_defense');

                $table->primary(['state_at', 'id']);
            });
        }

        if (!\Schema::hasTable('countries')) {
            \Schema::create('countries', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('flag')->nullable()->default(null);
                $table->tinyInteger('active')->unsigned()->default(1);
                $table->json('extra')->nullable()->default(null);

                $table->index('active');
            });
        }

        if (!\Schema::hasTable('countries_stat')) {
            \Schema::create('countries_stat', function (Blueprint $table) {
                $table->timestamp('state_at');
                $table->integer('id')->unsigned();
                $table->integer('pop')->unsigned();
                $table->integer('accounts')->unsigned();
                $table->integer('towns')->unsigned();
                $table->float('science');
                $table->float('production');
                $table->float('attack');
                $table->float('defense');
                // $table->integer('delta_pop');
                // $table->integer('delta_accounts');
                // $table->smallInteger('delta_towns');
                // $table->float('delta_science');
                // $table->float('delta_production');
                // $table->float('delta_attack');
                // $table->float('delta_defense');

                $table->primary(['state_at', 'id']);
            });
        }

        //"countries_diplomacy" => "
        //    CREATE TABLE IF NOT EXISTS `z_ruXX_countries_diplomacy` (
        //        `stateAt` TIMESTAMP NOT NULL,
        //        `id1` SMALLINT(5) UNSIGNED NOT NULL,
        //        `id2` SMALLINT(5) UNSIGNED NOT NULL,
        //        `status` TINYINT(3) UNSIGNED NOT NULL,
        //        PRIMARY KEY (`stateAt`, `id1`, `id2`),
        //        INDEX `status` (`status`),
        //        INDEX `stateAt_id1` (`stateAt`, `id1`)
        //    ) ENGINE=InnoDB;",

        if (!\Schema::hasTable('events')) {
            \Schema::create('events', function (Blueprint $table) {
                $table->timestamp('state_at');
                $table->integer('id')->unsigned();
                $table->integer('town_id')->unsigned()->nullable()->default(null);
                $table->integer('account_id')->unsigned()->default(null);
                $table->integer('country_id')->unsigned()->default(null);
                $table->integer('country_id_from')->unsigned()->default(null);
                $table->integer('role')->unsigned()->default(null);
                $table->json('extra')->nullable()->default(null);

                $table->index('id');
                $table->index('state_at');
            });
        }

        if (!\Schema::hasTable('common')) {
            \Schema::create('common', function (Blueprint $table) {
                $table->timestamp('state_at');

                $table->integer('towns_total')->unsigned()->default(0);
                $table->integer('towns_new')->unsigned()->default(0);
                $table->integer('towns_renamed')->unsigned()->default(0);
                $table->integer('towns_lost')->unsigned()->default(0);
                $table->integer('towns_destroy')->unsigned()->default(0);

                $table->integer('wonders_new')->unsigned()->default(0);
                $table->integer('wonders_destroy')->unsigned()->default(0);
                $table->integer('wonders_activate')->unsigned()->default(0);

                $table->integer('accounts_total')->unsigned()->default(0);
                $table->integer('accounts_active')->unsigned()->default(0);
                $table->integer('accounts_race0')->unsigned()->default(0);
                $table->integer('accounts_race1')->unsigned()->default(0);
                $table->integer('accounts_race2')->unsigned()->default(0);
                $table->integer('accounts_race3')->unsigned()->default(0);
                $table->integer('accounts_sex0')->unsigned()->default(0);
                $table->integer('accounts_sex1')->unsigned()->default(0);

                $table->integer('accounts_new')->unsigned()->default(0);
                $table->integer('accounts_country_change')->unsigned()->default(0);
                $table->integer('accounts_country_in')->unsigned()->default(0);
                $table->integer('accounts_country_out')->unsigned()->default(0);
                $table->integer('accounts_deleted')->unsigned()->default(0);
                $table->integer('accounts_renamed')->unsigned()->default(0);
                $table->integer('accounts_role_in')->unsigned()->default(0);
                $table->integer('accounts_role_out')->unsigned()->default(0);

                $table->integer('countries_total')->unsigned()->default(0);
                $table->integer('countries_new')->unsigned()->default(0);
                $table->integer('countries_renamed')->unsigned()->default(0);
                $table->integer('countries_flag')->unsigned()->default(0);
                $table->integer('countries_deleted')->unsigned()->default(0);

                $table->primary('state_at');
            });
        }

        $this->restorePrefix();
    }

    private function setWorldPrefix(string $sign)
    {
        $this->savedPrefix = DB::getTablePrefix();
        DB::setTablePrefix('z_'.$sign.'_');
    }

    private function restorePrefix()
    {
        DB::setTablePrefix($this->savedPrefix);
    }
}
