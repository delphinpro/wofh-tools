<?php

namespace Dolphin;


use Psr\Container\ContainerInterface;


/**
 * Class Command
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016 delphinpro
 * @license     Licensed under the MIT license
 * @package     Dolphin
 */
abstract class Command extends DolphinContainer
{
    protected $name;

    protected $action;

    protected $aliases = [];


    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->container['params'] = $this->parseParams($this->container['arguments']);
    }


    final public function bootEloquent()
    {
        $this->container->get('db');
    }


    final private function parseParams($args)
    {
        array_shift($args);
        $result = [];
        foreach ($args as $arg) {
            if (strpos($arg, '=') === false) {
                $arg .= '=true';
            }

            list($key, $value) = explode('=', $arg);

            if (array_key_exists($key, $this->aliases)) {
                $key = $this->aliases[$key];
            }

            $key = ltrim($key, '-');

            if (is_numeric($value)) {
                $value = (int)$value;
            }

            if ($value === 'true') {
                $value = true;
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
