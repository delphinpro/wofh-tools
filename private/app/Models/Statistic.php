<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */


namespace WofhTools\Models;


class Statistic
{
    protected $container;


    public function __construct(\Slim\Container $container)
    {
        $this->container = $container;
    }


    public function getCommonStat(string $sign)
    {
        /** @var \Illuminate\Database\Capsule\Manager $db */
        $db = $this->container->get('db');
        /** @var \Illuminate\Database\Connection $conn */
        $conn = $db->getConnection();

        $savePrefix = $conn->getTablePrefix();
        $conn->setTablePrefix('z_'.$sign.'_');
        $d = $conn->table('common')
            ->select()
//            ->orderBy('stateDate')
            ->latest('stateAt')
            ->get();

        $conn->setTablePrefix($savePrefix);

        return $d;
    }


    public function getCountriesList(string $sign)
    {
        /** @var \Illuminate\Database\Capsule\Manager $db */
        $db = $this->container->get('db');
        /** @var \Illuminate\Database\Connection $conn */
        $conn = $db->getConnection();

        $savePrefix = $conn->getTablePrefix();
        $conn->setTablePrefix('z_'.$sign.'_');
        $d = $conn->table('countries')
            ->select()
//            ->orderBy('stateAt')
            ->get();

        $conn->setTablePrefix($savePrefix);

        return $d;
    }
}
