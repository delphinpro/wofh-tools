<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020–2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait Checker
{
    private function checkTables()
    {
        withWorldPrefix(function () {
            $this->createTableTowns();
            $this->createTableTownsData();
            $this->createTableAccounts();
            $this->createTableAccountsData();
            $this->createTableCountries();
            $this->createTableCountriesData();
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
            $this->createTableEvents();
            $this->createTableCommon();
        }, $this->world);
    }

    private function createTableTowns()
    {
        if (Schema::hasTable('towns')) return;
        Schema::create('towns', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedSmallInteger('pop')->nullable();
            $table->unsignedSmallInteger('wonder')->nullable();
            $table->boolean('lost')->nullable()->default(false);
            $table->boolean('destroyed')->nullable()->default(false);
            $table->string('name');
            $table->json('names')->nullable();
        });
    }

    private function createTableTownsData()
    {
        if (Schema::hasTable('towns_data')) return;
        Schema::create('towns_data', function (Blueprint $table) {
            $table->timestamp('state_at');
            $table->unsignedInteger('id');
            $table->unsignedMediumInteger('pop');
            $table->unsignedSmallInteger('wonder_id')->nullable();
            $table->unsignedTinyInteger('wonder_level')->nullable();
            $table->smallInteger('delta_pop')->nullable();

            $table->primary(['state_at', 'id']);
            $table->index('pop');
        });
    }

    private function createTableAccounts()
    {
        if (Schema::hasTable('accounts')) return;
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('race')->default(0);
            $table->unsignedTinyInteger('sex')->default(0);
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('role')->default(0);
            $table->unsignedInteger('pop')->default(0);
            $table->unsignedSmallInteger('towns')->default(0);
            $table->float('science', 12)->default(0);
            $table->float('production', 12)->default(0);
            $table->float('attack', 12)->default(0);
            $table->float('defense', 12)->default(0);
            $table->boolean('active')->default(true);
            $table->string('name');
            $table->json('names')->nullable();
            $table->json('countries')->nullable();

            $table->index('country_id');
            $table->index('active');
        });
    }

    private function createTableAccountsData()
    {
        if (Schema::hasTable('accounts_data')) return;
        Schema::create('accounts_data', function (Blueprint $table) {
            $table->timestamp('state_at');
            $table->unsignedInteger('id');
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('role')->nullable();
            $table->unsignedInteger('pop')->nullable();
            $table->unsignedSmallInteger('towns');
            $table->float('science', 12);
            $table->float('production', 12);
            $table->float('attack', 12);
            $table->float('defense', 12);
            $table->integer('delta_pop');
            $table->smallInteger('delta_towns');
            $table->float('delta_science');
            $table->float('delta_production');
            $table->float('delta_attack');
            $table->float('delta_defense');

            $table->primary(['state_at', 'id']);
        });
    }

    private function createTableCountries()
    {
        if (Schema::hasTable('countries')) return;
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('flag')->nullable();
            $table->json('diplomacy')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedInteger('pop')->nullable();
            $table->unsignedInteger('accounts')->nullable();
            $table->unsignedInteger('towns')->nullable();
            $table->float('attack', 12)->nullable();
            $table->float('defense', 12)->nullable();
            $table->float('science', 12)->nullable();
            $table->float('production', 12)->nullable();
            $table->string('name');
            $table->json('names')->nullable();
            $table->json('flags')->nullable();
            $table->index('active');
        });
    }

    private function createTableCountriesData()
    {
        if (Schema::hasTable('countries_data')) return;
        Schema::create('countries_data', function (Blueprint $table) {
            $table->timestamp('state_at');
            $table->unsignedInteger('id');
            $table->unsignedInteger('pop');
            $table->unsignedInteger('accounts');
            $table->unsignedInteger('towns');
            $table->float('science', 12);
            $table->float('production', 12);
            $table->float('attack', 12);
            $table->float('defense', 12);
            $table->integer('delta_pop')->nullable();
            $table->integer('delta_accounts')->nullable();
            $table->mediumInteger('delta_towns')->nullable();
            $table->float('delta_science', 12)->nullable();
            $table->float('delta_production', 12)->nullable();
            $table->float('delta_attack', 12)->nullable();
            $table->float('delta_defense', 12)->nullable();

            $table->primary(['state_at', 'id']);
        });
    }

    private function createTableEvents()
    {
        if (Schema::hasTable('events')) return;
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

    private function createTableCommon()
    {
        if (Schema::hasTable('common')) return;
        Schema::create('common', function (Blueprint $table) {
            $table->timestamp('state_at');

            $table->unsignedInteger('towns_total')->default(0);
            $table->unsignedInteger('towns_created')->default(0);
            $table->unsignedInteger('towns_renamed')->default(0);
            $table->unsignedInteger('towns_lost')->default(0);
            $table->unsignedInteger('towns_destroyed')->default(0);

            $table->unsignedInteger('wonders_started')->default(0);
            $table->unsignedInteger('wonders_destroyed')->default(0);
            $table->unsignedInteger('wonders_activated')->default(0);

            $table->unsignedInteger('accounts_total')->default(0);
            $table->unsignedInteger('accounts_active')->default(0);
            $table->unsignedInteger('accounts_race0')->default(0);
            $table->unsignedInteger('accounts_race1')->default(0);
            $table->unsignedInteger('accounts_race2')->default(0);
            $table->unsignedInteger('accounts_race3')->default(0);
            $table->unsignedInteger('accounts_sex0')->default(0);
            $table->unsignedInteger('accounts_sex1')->default(0);

            $table->unsignedInteger('accounts_created')->default(0);
            $table->unsignedInteger('accounts_country_change')->default(0);
            $table->unsignedInteger('accounts_country_in')->default(0);
            $table->unsignedInteger('accounts_country_out')->default(0);
            $table->unsignedInteger('accounts_deleted')->default(0);
            $table->unsignedInteger('accounts_renamed')->default(0);
            $table->unsignedInteger('accounts_role_in')->default(0);
            $table->unsignedInteger('accounts_role_out')->default(0);
            $table->unsignedInteger('accounts_rating_hide')->default(0);
            $table->unsignedInteger('accounts_rating_show')->default(0);

            $table->unsignedInteger('countries_total')->default(0);
            $table->unsignedInteger('countries_created')->default(0);
            $table->unsignedInteger('countries_renamed')->default(0);
            $table->unsignedInteger('countries_flag')->default(0);
            $table->unsignedInteger('countries_deleted')->default(0);

            $table->primary('state_at');
        });
    }
}
