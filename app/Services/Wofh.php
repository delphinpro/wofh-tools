<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2016-2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Services;


use App\World;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Exceptions\WofhServiceException;


/**
 * Class Wofh
 *
 * @package App\Services
 */
class Wofh
{
    const STD_DATETIME = 'Y-m-d H:i:s';
    // const STD_DATE_H   = 'Y-m-d__H-00-00';
    const DATE_FILE    = 'Y-m-d_H';

    const SERVER_LANG_RU  = 1;
    const SERVER_LANG_EN  = 2;
    const SERVER_LANG_DE  = 3;
    const SERVER_LANG_INT = 4;

    const SERVER_TYPE_SPEED = 1;
    const SERVER_TYPE_TEST  = 2;

    private static $serversLang = [
        'ru'  => self::SERVER_LANG_RU,
        'en'  => self::SERVER_LANG_EN,
        'de'  => self::SERVER_LANG_DE,
        'int' => self::SERVER_LANG_INT,
    ];

    private static $serversType = [
        's' => self::SERVER_TYPE_SPEED,
        't' => self::SERVER_TYPE_TEST,
    ];

    private static $aliases = [
        'en' => 'int',
        'de' => 'int',
    ];

    private static $linkStatus = '/aj_statistics';

    private static $linkStatistic = '/aj_statistics';

    /** @var Json */
    private $json;


    public function __construct(Json $json)
    {
        $this->json = $json;
    }


    /**
     * Возвращает уникальный идентификатор мира для внутреннего использования
     *
     * @param int $langIndex  Индекс языка сервера игры. См. константы класса SERVER_LANG_*
     * @param int $worldIndex Порядковый номер мира
     * @param int $typeIndex  Индекс типа сервера игры. См. константы класса SERVER_TYPE_*
     *
     * @return int Уникальный идентификатор
     */
    public function makeWorldId(int $langIndex, int $worldIndex, int $typeIndex = 0): int
    {
        return $langIndex * 10000 + $typeIndex * 1000 + $worldIndex;
    }


    /**
     * Возвращает уникальный идентификатор мира по его сигнатуре
     *
     * @param string $sign Условное обозначение мира
     *
     * @return bool|int Уникальный идентификатор
     */
    public function signToId(string $sign): int
    {
        $reLanguages = join('|', array_keys(self::$serversLang));
        $reTypes = join('|', array_keys(self::$serversType));
        $regexp = '/^('.$reLanguages.')(\d+)('.$reTypes.')*$/Usi';

        // regexp example: /^(ru|en|de|int)(\d+)(s|t)*$/Usi
        if (preg_match($regexp, strtolower($sign), $m)) {
            $langIndex = self::$serversLang[$m[1]];
            $worldIndex = $m[2];
            $typeIndex = count($m) > 3 ? self::$serversType[$m[3]] : 0;

            return $this->makeWorldId($langIndex, $worldIndex, $typeIndex);
        }

        return false;
    }


    /**
     * Возвращает сигнатуру мира по его уникальному идентификатору
     *
     * @param int $id Идентификатор мира
     *
     * @return string Условное обозначение мира
     */
    public function idToSign(int $id): string
    {
        if ($id < 10001) {
            return '';
        }

        $num = $id % 1000;
        $attributes = (int)floor($id / 1000);
        $type = $this->getTypeOfServer($attributes % 10);
        $lang = $this->getLangOfServer((int)floor($attributes / 10));

        if ($lang === false) {
            return '';
        }

        if ($type === false) {
            return '';
        }

        return $lang.$num.$type;
    }


    /**
     * Возвращает идентификатор мира по домену игрового сервера
     *
     * @param string $domain Домен игрового сервера
     *
     * @return int Идентификатор мира
     */
    public function domainToId(string $domain): int
    {
        $domain = preg_replace('~^http[s]*://(.*)$~Usi', '\\1', $domain);

        if (preg_match('/\.waysofhistory\.com/', $domain)) {
            $sign = preg_replace('/\.waysofhistory\.com/', '', $domain);
            $id = $this->signToId($sign);

            if (20000 < $id && $id <= 20005) {
                return 0;
            }

            return $id;
        }

        return 0;
    }


    /**
     * Возвращает домен игрового сервера по идентификатору мира
     *
     * @param int  $id    Идентификатор мира
     * @param bool $https Добавить протокол
     *
     * @return string Домен игрового сервера
     */
    public function idToDomain(int $id, bool $https = false): string
    {
        $sign = $this->idToSign($id);

        return $sign ? ($https ? 'https://' : '').$sign.'.waysofhistory.com' : '';
    }


    /**
     * Возвращает ссылку на получение данных статистики
     *
     * @param int    $worldId Идентификатор мира
     * @param string $lang    Язык получаемых данных
     *
     * @return string
     */
    public function getStatisticLink(int $worldId, $lang = 'ru')
    {
        return $this->idToDomain($worldId, true).self::$linkStatistic.'?lang='.$lang;
    }


    /**
     * Возвращает ссылку для получения статуса миров
     *
     * @param string $lang Язык сервера
     *
     * @return bool|string Ссылка или false для неизвестного языка
     */
    public function makeStatusLink(string $lang)
    {
        if (!array_key_exists($lang, self::$serversLang)) {
            return false;
        }

        if (array_key_exists($lang, self::$aliases)) {
            $lang = self::$aliases[$lang];
        }

        return 'https://ru.waysofhistory.com'.self::$linkStatus.'?lang='.$lang;
    }


    /**
     * Возвращает список уникальных ссылок на получения статуса миров
     *
     * @return array
     */
    public function makeAllStatusLinks(): array
    {
        return array_unique(
            array_map(
                function ($lang) { return $this->makeStatusLink($lang); },
                array_keys(self::$serversLang)
            )
        );
    }


    /**
     * Загружаетданные о статусе миров с игровых серверов.
     *
     * @param array $links Массив ссылок для получения статуса
     *
     * @return array
     * @throws \App\Exceptions\WofhServiceException
     */
    public function loadStatusOfWorlds(array $links): array
    {
        try {
            $result = [];
            foreach ($links as $link) {
                $data = Http::get($link)
                    ->throw()
                    ->json();

                if (!array_key_exists('worlds', $data)) {
                    throw new WofhServiceException('Invalid data on the status of worlds. Perhaps the format has been changed.');
                }

                $result = array_merge($result, $data['worlds']);
            }
//
//            file_put_contents(
//                storage_path('logs/status_worlds.json'),
//                json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
//            );

            return $result;
        } catch (\Exception $e) {
            throw new WofhServiceException($e->getMessage());
        }
    }


    /**
     * Проверка и обновление статуса игровых миров
     *
     * @param array|null $links
     *
     * @throws \App\Exceptions\WofhServiceException
     */
    public function check(array $links = null): void
    {
        if (!$links) {
            $links = $this->makeAllStatusLinks();
        }

        $data = $this->loadStatusOfWorlds($links);

        foreach ($data as $domain => $status) {
            $id = $this->domainToId($domain);
            $sign = $this->idToSign($id);

            if ($id === 0) {
                continue;
            }

            $name = $status['name'];

            if (preg_match('/[\d]+\s-\s(\D+)$/Usi', $status['name'], $m)) {
                $name = $m[1];
            }

            $world = World::findOrNew($id);

            $world->id = $id;
            $world->title = $name;
            $world->sign = $sign;
            $world->can_reg = (bool)(int)$status['canreg'];
            $world->working = (bool)(int)$status['working'];
            $world->version = '1.4';

            // Для новых миров метка времени старта — текущее время
            if (!$world->started_at && $world->working) {
                $world->started_at = new Carbon();
            }

            $world->save();

            unset($world);
        }
    }


    /**
     * @param int $index
     *
     * @return false|string
     */
    private function getLangOfServer(int $index)
    {
        $a = array_flip(self::$serversLang);

        return array_key_exists($index, $a) ? $a[$index] : false;
    }


    /**
     * @param int $index
     *
     * @return false|string
     */
    private function getTypeOfServer(int $index)
    {
        if ($index === 0) {
            return '';
        }

        $a = array_flip(self::$serversType);

        return array_key_exists($index, $a) ? $a[$index] : false;
    }
}
