<?php

namespace Dolphin;


use Psr\Container\ContainerInterface;


/**
 * Class DolphinContainer
 * Dolphin command line interface
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 *
 * @package     Dolphin
 *
 * @property \Dolphin\Console                console
 * @property \Illuminate\Database\Connection db
 * @property \WofhTools\Core\AppSettings     config
 * @property \WofhTools\Helpers\FileSystem   fs
 * @property \WofhTools\Helpers\Http         http
 * @property \WofhTools\Helpers\Json         json
 * @property \WofhTools\Tools\Wofh           wofh
 * @property array                           params Параметры командной строки
 */
class DolphinContainer
{
    /** @var ContainerInterface */
    protected $container;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * @param $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function __get($id)
    {
        if ($this->container->has($id)) {
            return $this->container[$id];
        }

        throw new \Exception('Invalid DI container key: '.$id);
    }
}
