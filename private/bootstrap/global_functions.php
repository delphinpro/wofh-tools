<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

const CAST_TO_INT = 1;
const CAST_TO_FLOAT = 2;
const CAST_TO_BOOL = 3;

/**
 * @param string $cachePath
 * @param string $rootPath
 *
 * @return bool|string
 */
function prepareTwigCachePath(string $cachePath, string $rootPath)
{
    return realpath(
        $rootPath
        .DIRECTORY_SEPARATOR
        .trim(str_replace('/', DIRECTORY_SEPARATOR, $cachePath), DIRECTORY_SEPARATOR)
    );
}


/**
 * @param string $var
 * @param int    $castTo
 *
 * @return mixed
 */
function castVar(string $var, int $castTo)
{
    $filters = [
        CAST_TO_INT   => FILTER_VALIDATE_INT,
        CAST_TO_FLOAT => FILTER_VALIDATE_FLOAT,
        CAST_TO_BOOL  => FILTER_VALIDATE_BOOLEAN,
    ];

    $filter = array_key_exists($castTo, $filters) ? $filters[$castTo] : FILTER_DEFAULT;

    return filter_var($var, $filter);
}


function loadGlobalConfiguration(string $configDirectory): void
{
    if (!is_dir($configDirectory)) {
        echo 'Invalid config directory or not exists';
        die;
    }

    $env = \Dotenv\Dotenv::create($configDirectory);
    $env->load();
    $env->required('ENV_LOCATION')->notEmpty();

    $dotenv = \Dotenv\Dotenv::create($configDirectory, getenv('ENV_LOCATION'));
    $dotenv->overload();

    $dotenv->required('httpVersion')->notEmpty();
    $dotenv->required('responseChunkSize')->isInteger();
    $dotenv->required('outputBuffering')->notEmpty()->allowedValues(['false', 'append', 'prepend']);
    $dotenv->required('determineRouteBeforeAppMiddleware')->isBoolean();
    $dotenv->required('displayErrorDetails')->isBoolean();
    $dotenv->required('addContentLengthHeader')->isBoolean();
    $dotenv->required('routerCacheFile')->isBoolean();

    $_ENV['responseChunkSize'] = castVar($_ENV['responseChunkSize'], CAST_TO_INT);
    $_ENV['outputBuffering'] = $_ENV['outputBuffering'] === 'false' ? false : $_ENV['outputBuffering'];
    $_ENV['determineRouteBeforeAppMiddleware'] = castVar($_ENV['determineRouteBeforeAppMiddleware'], CAST_TO_BOOL);
    $_ENV['displayErrorDetails'] = castVar($_ENV['displayErrorDetails'], CAST_TO_BOOL);
    $_ENV['addContentLengthHeader'] = castVar($_ENV['addContentLengthHeader'], CAST_TO_BOOL);
    $_ENV['routerCacheFile'] = castVar($_ENV['routerCacheFile'], CAST_TO_BOOL);
    $_ENV['DEBUG'] = castVar($_ENV['DEBUG'], CAST_TO_BOOL);
    $_ENV['ssrEnabled'] = castVar($_ENV['ssrEnabled'], CAST_TO_BOOL);
    $_ENV['twigCacheEnabled'] = castVar($_ENV['twigCacheEnabled'], CAST_TO_BOOL);
    $_ENV['statLoadInterval'] = castVar($_ENV['statLoadInterval'], CAST_TO_INT);
    $_ENV['JWT_LIFETIME'] = castVar($_ENV['JWT_LIFETIME'], CAST_TO_INT);
}


/**
 * Возвращает массив настроек, полученных из $_ENV
 *
 * @return array
 */
function getConfigFromEnv(): array
{
    $ssrBundle = DIR_ROOT
        .DIRECTORY_SEPARATOR.trim(env('publicHtmlDir'), '\\/')
        .DIRECTORY_SEPARATOR.trim(env('ssrBundle'), '\\/');

    return [
        'httpVersion'                       => env('httpVersion'),
        'responseChunkSize'                 => env('responseChunkSize'),
        'outputBuffering'                   => env('outputBuffering'),
        'determineRouteBeforeAppMiddleware' => env('determineRouteBeforeAppMiddleware'),
        'displayErrorDetails'               => env('displayErrorDetails'),
        'addContentLengthHeader'            => env('addContentLengthHeader'),
        'routerCacheFile'                   => env('routerCacheFile'),

        'debug' => env('debug'),

        'ssrEnabled' => env('ssrEnabled'),
        'ssrBundle'  => $ssrBundle,

        'twigCacheEnabled' => env('twigCacheEnabled'),
        'twigCachePath'    => env('twigCachePath'),

        'statisticPath'    => env('statisticPath'),
        'statLoadInterval' => env('statLoadInterval'),

        'rootPath'      => DIR_ROOT,
        'publicHtmlDir' => env('publicHtmlDir'),

        'db' => [
            'driver'    => env('DB_DRIVER'),
            'host'      => env('DB_HOST'),
            'database'  => env('DB_DATABASE'),
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD'),
            'charset'   => env('DB_CHARSET'),
            'collation' => env('DB_COLLATION'),
            'prefix'    => env('DB_PREFIX'),
        ],
    ];
}


/**
 * @param array $settings
 *
 * @return \Illuminate\Database\Capsule\Manager
 */
function bootEloquent(array $settings): \Illuminate\Database\Capsule\Manager
{
    $capsule = new \Illuminate\Database\Capsule\Manager();
    $capsule->addConnection($settings);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
}


function GUID()
{
    mt_srand((double)microtime() * 10000);
    $charId = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45);// "-"
    $uuid = chr(123)// "{"
        .substr($charId, 0, 8).$hyphen
        .substr($charId, 8, 4).$hyphen
        .substr($charId, 12, 4).$hyphen
        .substr($charId, 16, 4).$hyphen
        .substr($charId, 20, 12)
        .chr(125);// "}"
    return $uuid;
}

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
