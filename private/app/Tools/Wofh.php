<?php

namespace WofhTools\Tools;


use WofhTools\Helpers\Http;
use WofhTools\Helpers\HttpCustomException;
use WofhTools\Helpers\Json;
use WofhTools\Helpers\JsonCustomException;
use WofhTools\Models\Worlds;


/**
 * Class Wofh
 * Game data processing
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016—2019 delphinpro
 * @license     Licensed under the MIT license
 *
 * @package     WofhTools\Tools
 */
class Wofh
{
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

    /** @var Http */
    private $http;

    /** @var Json */
    private $json;


    public function __construct(Http $http, Json $json)
    {
        $this->http = $http;
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

        $domain = $sign ? ($https ? 'https://' : '').$sign.'.waysofhistory.com' : '';

        return $domain;
    }


    /**
     * Возвращает ссылку для получения статуса миров
     *
     * @param string $lang Язык сервера
     *
     * @return bool|string Ссылка или false для неизвестного языка
     */
    public function getStatusLink(string $lang)
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
    public function getAllStatusLinks(): array
    {
        $links = array_unique(
            array_map(
                function ($lang) { return $this->getStatusLink($lang); },
                array_keys(self::$serversLang)
            )
        );

        return $links;
    }


    /**
     * Загружаетданные о статусе миров с игровых серверов.
     *
     * @param array $links Массив ссылок для получения статуса
     *
     * @return array
     * @throws WofhException
     */
    public function loadStatusOfWorlds(array $links): array
    {
        try {
            $result = [];
            foreach ($links as $link) {
                $json = $this->http->readUrl($link);
                $data = $this->json->decode($json);

                if (!array_key_exists('worlds', $data)) {
                    throw new WofhException('Invalid data on the status of worlds. Perhaps the format has been changed.');
                }

                $result = array_merge($result, $data['worlds']);
            }

            return $result;
        } catch (HttpCustomException $e) {
            throw new WofhException($e->getMessage());
        } catch (JsonCustomException $e) {
            throw new WofhException($e->getMessage());
        }
    }


    /**
     * Проверка и обновление статуса игровых миров
     *
     * @param array $links
     *
     * @return void
     * @throws WofhException
     */
    public function check(array $links): void
    {
        $data = $this->loadStatusOfWorlds($links);

        foreach ($data as $domain => $status) {
            $id = $this->domainToId($domain);
            $sign = $this->idToSign($id);

            if ($id === 0) {
                continue;
            }

            $name = $status['name'];

            if (preg_match('/[\d]+\s\-\s(\D+)$/Usi', $status['name'], $m)) {
                $name = $m[1];
            }

            /** @var Worlds $world */
            $world = Worlds::findOrNew($id);

            $world->id = $id;
            $world->title = $name;
            $world->sign = $sign;
            $world->can_reg = (bool)(int)$status['canreg'];
            $world->working = (bool)(int)$status['working'];
            $world->version = '1.4';

            $world->save();

            unset($world);
        }
    }


    private function getLangOfServer(int $index)
    {
        $a = array_flip(self::$serversLang);

        return array_key_exists($index, $a) ? $a[$index] : false;
    }


    private function getTypeOfServer(int $index)
    {
        if ($index === 0) {
            return '';
        }

        $a = array_flip(self::$serversType);

        return array_key_exists($index, $a) ? $a[$index] : false;
    }
}
