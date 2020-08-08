<?php

namespace WofhTools\Core;


/**
 * Class VueRenderer
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Core
 */
class VueRenderer
{
    /** @var string */
    private $nodePath;

    /** @var \V8Js */
    private $v8;


    /**
     * @param string $nodeModulesPath
     *
     * @return void
     */
    public function __construct(string $nodeModulesPath)
    {
        $this->nodePath = rtrim($nodeModulesPath, '/\\').DIRECTORY_SEPARATOR;
        $this->v8 = new \V8Js();
    }


    /**
     * @param string $entry
     * @param array  $data
     *
     * @return string
     * @throws \V8JsScriptException
     */
    public function render(string $entry, array $data): string
    {
        $state = json_encode($data);
        $app = file_get_contents($entry);

        ob_start();

        try {
            $this->setupVueRenderer();
            $this->v8->executeString("var __PRELOAD_STATE__ = ${state}; this.global.__PRELOAD_STATE__ = __PRELOAD_STATE__;");
            $this->v8->executeString($app);
        } catch (\V8JsScriptException $e) {
            ob_end_clean();
            throw $e;
        }

        return ob_get_clean();
    }


    private function setupVueRenderer()
    {
        $prepareCode = 'var process={env:{VUE_ENV:"server",NODE_ENV:"production"}};this.global={process:process};';
//        $vueSource = file_get_contents($this->nodePath.'vue/dist/vue.js');
        $rendererSource = file_get_contents($this->nodePath.'vue-server-renderer/basic.js');

        $this->v8->executeString($prepareCode);
//        $this->v8->executeString($vueSource);
        $this->v8->executeString($rendererSource);
    }
}
