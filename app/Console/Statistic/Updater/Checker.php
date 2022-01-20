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
use Illuminate\Support\Facades\Schema;

/**
 * Class UpdateChecker
 *
 * @package App\Console\Traits
 */
trait Checker
{
    private ?string $savedPrefix;

    protected function checkTables(World $world)
    {
        $this->setWorldPrefix($world->sign);

        if (!Schema::hasTable('towns')) {
            Schema::create('towns', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->unsignedInteger('account_id')->nullable();
                $table->boolean('lost')->nullable()->default(false);
                $table->boolean('destroy')->nullable()->default(false);
                $table->json('props')->nullable();
            });
        }

        if (!Schema::hasTable('towns_stat')) {
            Schema::create('towns_stat', function (Blueprint $table) {
                $table->timestamp('state_at');
                $table->unsignedInteger('id');
                $table->unsignedMediumInteger('pop');
                $table->unsignedSmallInteger('wonder_id')->nullable();
                $table->unsignedTinyInteger('wonder_level')->nullable();
                // $table->smallInteger('delta_pop')->unsigned()->nullable()->default(null);

                $table->primary(['state_at', 'id']);
                $table->index('pop');
            });
        }

        if (!Schema::hasTable('accounts')) {
            Schema::create('accounts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->unsignedTinyInteger('race')->default(0);
                $table->unsignedTinyInteger('sex')->default(0);
                $table->unsignedInteger('country_id')->nullable();
                $table->unsignedInteger('role')->default(0);
                $table->boolean('active')->default(true);
                $table->json('props')->nullable();

                $table->index('country_id');
                $table->index('active');
            });
        }

        if (!Schema::hasTable('accounts_stat')) {
            Schema::create('accounts_stat', function (Blueprint $table) {
                $table->timestamp('state_at');
                $table->unsignedInteger('id');
                $table->unsignedInteger('country_id')->nullable();
                $table->unsignedInteger('role')->nullable();
                $table->unsignedInteger('pop')->nullable();
                $table->unsignedSmallInteger('towns');
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

        if (!Schema::hasTable('countries')) {
            Schema::create('countries', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('flag')->nullable();
                $table->boolean('active')->default(true);
                $table->json('props')->nullable();

                $table->index('active');
            });
        }

        if (!Schema::hasTable('countries_stat')) {
            Schema::create('countries_stat', function (Blueprint $table) {
                $table->timestamp('state_at');
                $table->unsignedInteger('id');
                $table->unsignedInteger('pop');
                $table->unsignedInteger('accounts');
                $table->unsignedInteger('towns');
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

        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->timestamp('state_at');
                $table->unsignedInteger('id');
                $table->unsignedInteger('town_id')->nullable();
                $table->unsignedInteger('account_id')->nullable();
                $table->unsignedInteger('country_id')->nullable();
                $table->unsignedInteger('country_id_from')->nullable();
                $table->unsignedInteger('role')->nullable();
                $table->json('props')->nullable();

                $table->index('id');
                $table->index('state_at');
            });
        }

        if (!Schema::hasTable('common')) {
            Schema::create('common', function (Blueprint $table) {
                $table->timestamp('state_at');

                $table->unsignedInteger('towns_total')->default(0);
                $table->unsignedInteger('towns_new')->default(0);
                $table->unsignedInteger('towns_renamed')->default(0);
                $table->unsignedInteger('towns_lost')->default(0);
                $table->unsignedInteger('towns_destroy')->default(0);

                $table->unsignedInteger('wonders_new')->default(0);
                $table->unsignedInteger('wonders_destroy')->default(0);
                $table->unsignedInteger('wonders_activate')->default(0);

                $table->unsignedInteger('accounts_total')->default(0);
                $table->unsignedInteger('accounts_active')->default(0);
                $table->unsignedInteger('accounts_race0')->default(0);
                $table->unsignedInteger('accounts_race1')->default(0);
                $table->unsignedInteger('accounts_race2')->default(0);
                $table->unsignedInteger('accounts_race3')->default(0);
                $table->unsignedInteger('accounts_sex0')->default(0);
                $table->unsignedInteger('accounts_sex1')->default(0);

                $table->unsignedInteger('accounts_new')->default(0);
                $table->unsignedInteger('accounts_country_change')->default(0);
                $table->unsignedInteger('accounts_country_in')->default(0);
                $table->unsignedInteger('accounts_country_out')->default(0);
                $table->unsignedInteger('accounts_deleted')->default(0);
                $table->unsignedInteger('accounts_renamed')->default(0);
                $table->unsignedInteger('accounts_role_in')->default(0);
                $table->unsignedInteger('accounts_role_out')->default(0);

                $table->unsignedInteger('countries_total')->default(0);
                $table->unsignedInteger('countries_new')->default(0);
                $table->unsignedInteger('countries_renamed')->default(0);
                $table->unsignedInteger('countries_flag')->default(0);
                $table->unsignedInteger('countries_deleted')->default(0);

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
