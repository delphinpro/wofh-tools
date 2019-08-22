<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2013—2019 delphinpro
 * @license     licensed under the MIT license
 */

defined('DIR_ROOT') or define('DIR_ROOT', realpath('../'));

function getRemoteFile($url, $referrer)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, '');
    curl_setopt($ch, CURLOPT_REFERER, $referrer);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}


function router($_uri)
{
    $processor = new Processor();
     list($uri, $qs) = explode('?', trim($_uri, '/'), 2);
    $segments = explode('/', $uri);

    if ($qs === 'test') {
        $processor->debug = true;
    }

    if (count($segments) !== 3 or $segments[0] !== 'flags') {
        $processor->error('Wrong request');
    }

    $sign = $segments[1];
    $flag = $segments[2];

    if (!preg_match('`^(ru|en|de|int)(\d+)([st])*$`Us', $sign, $m)) {
        $processor->error('Wrong world identifier');
    }

    if (!preg_match('`^[a-z0-9]+\.gif$`Usi', $flag)) {
        $processor->error('Wrong flag identifier');
    }

    $processor->sign = strtolower($sign);
    $processor->flag = str_replace('.gif', '', $flag);
    $processor->country = $m[1];
    $processor->worldNum = (int)$m[2];
    $processor->worldType = !empty($m[3]) ? $m[3] : '';

    return $processor;
}


class Processor
{
    public $sign;
    public $flag;
    public $country;
    public $worldNum;
    public $worldType;
    public $directory;
    public $serverUrl;
    public $debug = false;


    public function error($message = '')
    {
        if (headers_sent() or $this->debug) {
            echo PHP_EOL.'<br>'.'ERROR: '.$message;
            echo PHP_EOL.'<br>'.'<pre>';
            echo print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), true);
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


$processor = router($_SERVER['REQUEST_URI']);
$processor->makeFlagDirectories();
$processor->defineServerUrl();

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
