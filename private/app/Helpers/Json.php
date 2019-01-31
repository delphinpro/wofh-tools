<?php

namespace WofhTools\Helpers;


/**
 * JSON utilities
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2014–2019 delphinpro
 * @license     Licensed under the MIT license
 * @package     WofhTools\Helpers
 */
class Json
{
    /**
     * Возвращает декодированные JSON-данные в виде массива
     *
     * @param string $json  JSON-строка
     * @param bool   $assoc Преобразовывать в ассоциативный массив
     * @param int    $depth Глубина рекурсии
     *
     * @return mixed
     * @throws JsonCustomException
     */
    public function decode(string $json, bool $assoc = true, int $depth = 512)
    {
        $data = json_decode($json, $assoc, $depth);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonCustomException(json_last_error_msg());
        }

        return $data;
    }


    /**
     * Возвращает кодированную JSON-строку
     *
     * @param mixed $value  Данные для кодирования
     * @param bool  $pretty Форматировать вывод
     *
     * @return string
     * @throws JsonCustomException
     */
    public function encode($value, bool $pretty = false): string
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

        $json = json_encode($value, $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonCustomException(json_last_error_msg());
        }

        return $json;
    }
}
