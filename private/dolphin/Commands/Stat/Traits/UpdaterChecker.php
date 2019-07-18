<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */


namespace Dolphin\Commands\Stat\Traits;


use Dolphin\Commands\Stat\Updater;
use Dolphin\Console;
use WofhTools\Models\Worlds;


/**
 * Trait UpdaterChecker
 *
 * @package Dolphin\Commands\Stat\Traits
 * @property \Illuminate\Database\Connection db
 * @property \Dolphin\Console                console
 */
trait UpdaterChecker
{
    public function checkTables(Worlds $world)
    {
        $sign = $world->sign;

//        $world->setWorldPrefix();
//        Console::writeLn(Capsule::schema()->hasTable('towns') ? 'Table "towns" exists' : 'Table "towns" NOT exists');
//        if (!Capsule::schema()->hasTable('towns')) {
//            Capsule::schema()->create('towns', function (Blueprint $table) {
//                $table->integer('townId', false, true);
//                $table->string('townTitle', 50);
//                $table->mediumInteger('accountId', false, true);
//                $table->tinyInteger('lost', false, true)->default(0);
//                $table->tinyInteger('destroyed', false, true)->default(0);
//                $table->text('extra');
//                $table->primary('townId');
//                $table->engine = 'InnoDB';
//            });
//        }
//        $world->restoreWorldPrefix();

        $queries = [
            'towns' => "
				CREATE TABLE IF NOT EXISTS `z_ruXX_towns` (
					`townId` INT(10) UNSIGNED NOT NULL,
					`townTitle` VARCHAR(50) NOT NULL,
					`accountId` MEDIUMINT(9) UNSIGNED NOT NULL,
					`lost` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
					`destroyed` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
					`extra` JSON NULL DEFAULT NULL,
					PRIMARY KEY (`townId`)
				) COLLATE='utf8_general_ci' ENGINE=InnoDB;",

            'towns_stat' => "
				CREATE TABLE IF NOT EXISTS `z_ruXX_towns_stat` (
					`stateDate` TIMESTAMP NOT NULL,
					`townId` MEDIUMINT(6) UNSIGNED NOT NULL,
					`accountId` SMALLINT(6) UNSIGNED NOT NULL,
					`pop` MEDIUMINT(10) NULL DEFAULT NULL,
					`wonderId` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
					`wonderLevel` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
					`delta` SMALLINT(6) NULL DEFAULT NULL,
					PRIMARY KEY (`stateDate`, `townId`),
					INDEX `stateDate` (`stateDate`),
					INDEX `townId` (`townId`),
					INDEX `townPop` (`pop`)
				) COLLATE='utf8_general_ci' ENGINE=InnoDB;",

            "accounts" => "
				CREATE TABLE IF NOT EXISTS `z_ruXX_accounts` (
					`accountId` MEDIUMINT(11) UNSIGNED NOT NULL,
					`accountName` VARCHAR(30) NOT NULL,
					`accountRace` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
					`accountSex` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
					`countryId` SMALLINT(11) UNSIGNED NOT NULL DEFAULT '0',
					`role` TINYINT(2) UNSIGNED NOT NULL DEFAULT '0',
					`active` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
					`extra` JSON NULL DEFAULT NULL,
					PRIMARY KEY (`accountId`),
					INDEX `countryId` (`countryId`),
					INDEX `active` (`active`)
				) COLLATE='utf8_general_ci' ENGINE=InnoDB;",

            "accounts_stat" => "
				CREATE TABLE IF NOT EXISTS `z_ruXX_accounts_stat` (
					`stateDate` TIMESTAMP NOT NULL,
					`accountId` MEDIUMINT(10) UNSIGNED NOT NULL,
					`countryId` SMALLINT(10) UNSIGNED NOT NULL DEFAULT '0',
					`role` TINYINT(2) UNSIGNED NOT NULL DEFAULT '0',
					`pop` MEDIUMINT(10) NOT NULL DEFAULT '0',
					`towns` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
					`science` FLOAT NOT NULL DEFAULT '0',
					`production` FLOAT NOT NULL DEFAULT '0',
					`attack` FLOAT NOT NULL DEFAULT '0',
					`defense` FLOAT NOT NULL DEFAULT '0',
					`deltaPop` MEDIUMINT(8) NOT NULL DEFAULT '0',
					`deltaTowns` TINYINT(4) NOT NULL DEFAULT '0',
					`deltaScience` FLOAT NOT NULL DEFAULT '0',
					`deltaProduction` FLOAT NOT NULL DEFAULT '0',
					`deltaAttack` FLOAT NOT NULL DEFAULT '0',
					`deltaDefense` FLOAT NOT NULL DEFAULT '0',
					PRIMARY KEY (`stateDate`, `accountId`),
					INDEX `accountId` (`accountId`),
					INDEX `stateDate` (`stateDate`)
				) COLLATE='utf8_general_ci' ENGINE=InnoDB;",

            "countries" => "
				CREATE TABLE IF NOT EXISTS `z_ruXX_countries` (
					`countryId` SMALLINT(5) UNSIGNED NOT NULL,
					`countryTitle` VARCHAR(30) NULL DEFAULT NULL,
					`countryFlag` VARCHAR(30) NULL DEFAULT NULL,
					`active` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
					`extra` JSON NULL DEFAULT NULL,
					PRIMARY KEY (`countryId`),
					INDEX `active` (`active`)
				) COLLATE='utf8_general_ci' ENGINE=InnoDB;",

            "countries_stat" => "
				CREATE TABLE IF NOT EXISTS `z_ruXX_countries_stat` (
					`stateDate` TIMESTAMP NOT NULL,
					`countryId` SMALLINT(5) UNSIGNED NOT NULL,
					`pop` INT(10) UNSIGNED NOT NULL DEFAULT '0',
					`accounts` TINYINT(10) UNSIGNED NOT NULL DEFAULT '0',
					`towns` SMALLINT(10) UNSIGNED NOT NULL DEFAULT '0',
					`science` FLOAT NULL DEFAULT NULL,
					`production` FLOAT NULL DEFAULT NULL,
					`attack` FLOAT NULL DEFAULT NULL,
					`defense` FLOAT NULL DEFAULT NULL,
					`deltaPop` MEDIUMINT(8) NULL DEFAULT NULL,
					`deltaAccounts` TINYINT(4) NULL DEFAULT NULL,
					`deltaTowns` SMALLINT(6) NULL DEFAULT NULL,
					`deltaScience` FLOAT NULL DEFAULT NULL,
					`deltaProduction` FLOAT NULL DEFAULT NULL,
					`deltaAttack` FLOAT NULL DEFAULT NULL,
					`deltaDefense` FLOAT NULL DEFAULT NULL,
					PRIMARY KEY (`stateDate`, `countryId`)
				) COLLATE='utf8_general_ci' ENGINE=InnoDB;",

            "countries_diplomacy" => "
				CREATE TABLE IF NOT EXISTS `z_ruXX_countries_diplomacy` (
					`stateDate` TIMESTAMP NOT NULL,
					`id1` SMALLINT(5) UNSIGNED NOT NULL,
					`id2` SMALLINT(5) UNSIGNED NOT NULL,
					`status` TINYINT(3) UNSIGNED NOT NULL,
					PRIMARY KEY (`stateDate`, `id1`, `id2`),
					INDEX `status` (`status`),
					INDEX `stateDate_id1` (`stateDate`, `id1`)
				) ENGINE=InnoDB;",

            "events" => "
				CREATE TABLE IF NOT EXISTS `z_ruXX_events` (
					`stateDate` TIMESTAMP NOT NULL,
					`eventId` INT(11) NOT NULL,
					`townId` INT(11) NOT NULL,
					`accountId` INT(11) NOT NULL,
					`countryId` INT(11) NOT NULL,
					`countryIdFrom` INT(11) NOT NULL,
					`role` INT(11) NOT NULL,
					`extra` JSON NULL DEFAULT NULL,
					INDEX `eventId` (`eventId`),
					INDEX `stateDate` (`stateDate`)
				) COLLATE='utf8_general_ci' ENGINE=InnoDB;",

            "common" => "
				CREATE TABLE IF NOT EXISTS `z_ruXX_common` (
					`stateDate` TIMESTAMP NOT NULL,

					`townsTotal`            SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`townsNew`              SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`townsRenamed`          SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`townsLost`             SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`townsDestroy`          SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',

					`wondersNew`            SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`wondersDestroy`        SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`wondersActivate`       SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',

					`accountsTotal`         SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`accountsActive`        SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`accountsRace0`         SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`accountsRace1`         SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`accountsRace2`         SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`accountsRace3`         SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`accountsSex0`          SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`accountsSex1`          SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',

					`accountsNew`           SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`accountsCountryChange` SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`accountsCountryIn`     SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`accountsCountryOut`    SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`accountsDeleted`       SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`accountsRenamed`       SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`accountsRoleIn`        SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`accountsRoleOut`       SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',

					`countriesTotal`        SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`countriesNew`          SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`countriesRenamed`      SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`countriesFlag`         SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
					`countriesDeleted`      SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',

					PRIMARY KEY (`stateDate`)
				) COLLATE='utf8_general_ci' ENGINE=InnoDB;",
        ];

        foreach ($queries as $query) {
            $this->db->statement(str_replace('ruXX', $sign, $query));
        }
    }


    public function checkStructure()
    {
        $this->console->writeFixedWidth('Check structure', Updater::PRINT_WIDTH);
        $this->console->write('Skip', Console::GREEN);
    }
}
