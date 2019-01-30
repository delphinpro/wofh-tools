<?php

namespace WofhTools\Helpers;


/**
 * Class Http
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2019 delphinpro
 * @license     Licensed under the MIT license
 * @package     WofhTools\Helpers
 */
class Http
{
    /**
     * Читает файл по HTTP с помощью cURL, и возвращает его содержимое.
     * В случае ошибкаи выбрасывается исключение.
     *
     * @param string $url     URL файла
     * @param int    $timeout Таймаут запроса
     *
     * @return string Прочтенные данные
     * @throws HttpCustomException
     */
    public function readUrl(string $url, int $timeout = 5): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($data === false or $errno !== 0) {
            throw new HttpCustomException('Error reading URL: '.$error.': '.$url);
        }

        return $data;
    }
}
