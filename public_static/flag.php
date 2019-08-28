<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2013—2019 delphinpro
 * @license     licensed under the MIT license
 */

use WofhTools\Tools\Wofh;
use WofhTools\Helpers\Http;
use WofhTools\Helpers\Json;


defined('DIR_ROOT') or define('DIR_ROOT', realpath('../'));

require_once '../vendor/autoload.php';
require_once '../private/bootstrap/global_functions.php';

loadGlobalConfiguration(realpath('../config'));


class Processor
{
    public $sign;
    public $worldId;
    public $flag;
    public $country;
    public $worldNum;
    public $worldType;
    public $directory;
    public $serverUrl;
    public $debug = false;

    /** @var \WofhTools\Tools\Wofh */
    private $wofh;


    public function __construct()
    {
        $this->wofh = new Wofh(new Http(), new Json());
    }


    public function parseUri($_uri)
    {
        list($uri, $qs) = explode('?', trim($_uri, '/'), 2);
        $segments = explode('/', $uri);

        if ($qs === 'test') {
            $this->debug = true;
        }

        if (count($segments) !== 3 or $segments[0] !== 'flags') {
            $this->error('Wrong request');
        }

        $sign = $segments[1];
        $flag = $segments[2];

        if (!preg_match('`^(ru|en|de|int)(\d+)([st])*$`Us', $sign, $m)) {
            $this->error('Wrong world identifier');
        }

        if (!preg_match('`^[a-z0-9]+\.gif$`Usi', $flag)) {
            $this->error('Wrong flag identifier');
        }

        $this->sign = strtolower($sign);
        $this->flag = str_replace('.gif', '', $flag);
        $this->country = $m[1];
        $this->worldNum = (int)$m[2];
        $this->worldType = !empty($m[3]) ? $m[3] : '';
        $this->worldId = $this->wofh->signToId($this->sign);
    }


    public function error($message = '')
    {
        if (headers_sent() or $this->debug) {
            echo PHP_EOL.'<br>'.'ERROR: '.$message;
            echo PHP_EOL.'<br>'.'<pre>';
            echo print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), true);
            echo print_r($this, true);
            echo '</pre>';
        } else {
            $defaultFlagPath = __DIR__.'/defaults/no-flag.png';
            header("Content-Type: image/png");
            readfile($defaultFlagPath);
        }
        exit;
    }


    public function makeFlagDirectories()
    {
        $relPath = '/flags/'.$this->sign;
        $this->directory = __DIR__.str_replace('/', DIRECTORY_SEPARATOR, $relPath);

        $chunks = explode('/', trim($relPath, '/'));
        try {
            $subDir = '';
            foreach ($chunks as $chunk) {
                $subDir .= DIRECTORY_SEPARATOR.$chunk;
                if (!is_dir(__DIR__.$subDir)) {
                    if (!mkdir(__DIR__.$subDir, 0777)) {
                        throw new Exception();
                    }
                    chmod(__DIR__.$subDir, 0777);
                }
            }
        } catch (\Exception $e) {
            $this->error('Can not make dir tree');
        }
    }


    public function checkWorld()
    {
        try {

            $dsn = ''.env('DB_DRIVER').':dbname='.env('DB_DATABASE').';host='.env('DB_HOST').'';
            $pdo = new \PDO($dsn, env('DB_USERNAME'), env('DB_PASSWORD'));

            $stmt = $pdo->prepare('SELECT * FROM `wt_worlds` WHERE id = :id');
            $stmt->execute(['id' => $this->worldId]);
            $res =$stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (!intval($res['working'])) {
                $stmt = $pdo->prepare('SELECT id FROM `wt_worlds` WHERE working = 1 AND id < 11000 ORDER BY id DESC LIMIT 1');
                $stmt->execute();
                $res =$stmt->fetchAll(\PDO::FETCH_ASSOC);
            }

            $this->serverUrl = $this->wofh->idToDomain($res[0]['id'], true);

        } catch (PDOException $e) {

            $this->error('Could not connect: '.$e->getMessage());

        }
    }


    public function defineServerUrl()
    {
        switch ($this->country) {
            case 'ru':
                $this->serverUrl = "https://ru".$this->worldNum.$this->worldType.".waysofhistory.com";
                break;
            case 'int':
                $this->serverUrl = "https://int".$this->worldNum.$this->worldType.".waysofhistory.com";
                break;
            default:
                $this->error('Undefined server base url');
        }
    }


    public function flagUrl()
    {
        return '/gen/flag/'.$this->flag.'.gif';
    }
}


$processor = new Processor();
$processor->parseUri($_SERVER['REQUEST_URI']);
$processor->makeFlagDirectories();
$processor->defineServerUrl();
$processor->checkWorld();

$flagFilename = __DIR__.'/flags/'.$processor->sign.'/'.$processor->flag.'.gif';

$bin = getRemoteFile($processor->serverUrl.$processor->flagUrl(), $processor->serverUrl.'/');

if ($bin !== false) {
    file_put_contents($flagFilename, $bin);
    chmod($flagFilename, 0777);

    header("Content-Type: image/gif");
    readfile($flagFilename);
    exit;
}

$processor->error('last');
