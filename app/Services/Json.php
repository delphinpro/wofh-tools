<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2014-2020 delphinpro
 * @license     licensed under the MIT license
 */


namespace App\Services;


use App\Exceptions\JsonServiceException;


class Json
{
    /**
     * Возвращает декодированные JSON-данные в виде массива
     *
     * @param string $json   JSON-строка
     * @param bool   $assoc  Преобразовывать в ассоциативный массив
     * @param int    $depth  Глубина рекурсии
     * @return mixed
     * @throws JsonServiceException
     */
    public function decode(string $json, bool $assoc = true, int $depth = 512)
    {
        $data = json_decode($json, $assoc, $depth);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonServiceException('JSON: '.json_last_error_msg());
        }

        return $data;
    }


    /**
     * Возвращает кодированную JSON-строку
     *
     * @param mixed $value        Данные для кодирования
     * @param bool  $pretty       Форматировать вывод
     * @param bool  $forceObject  Кодировать массивы как объекты
     * @return string
     * @throws JsonServiceException
     */
    public function encode($value, bool $pretty = false, bool $forceObject = false): string
    {
        $options = JSON_NUMERIC_CHECK
            | JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE
            | JSON_HEX_APOS
            | JSON_HEX_QUOT
            | JSON_PRESERVE_ZERO_FRACTION;

        if ($pretty) {
            $options = $options | JSON_PRETTY_PRINT;
        }

        if ($forceObject) {
            $options = $options | JSON_FORCE_OBJECT;
        }

        $json = json_encode($value, $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonServiceException(json_last_error_msg());
        }

        return $json;
    }
}
