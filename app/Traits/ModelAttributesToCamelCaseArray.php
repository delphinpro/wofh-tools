<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Traits;


trait ModelAttributesToCamelCaseArray
{
    public function attributesToCamelCaseArray(): array
    {
        return array_merge(
            $this->arraySnakeToCamelCase(parent::toArray()),
            $this->dateFieldsAsTimestamps($this->dates)
        );
    }

    /**
     * Преобразует атрибуты времени модели в таймстампы
     *
     * @param  string[]  $fieldNames
     * @return array
     */
    private function dateFieldsAsTimestamps(array $fieldNames): array
    {
        $dates = [];
        foreach ($fieldNames as $fieldName) {
            /** @var \Carbon\Carbon $carbon */
            $carbon = $this->{$fieldName};
            $key = $this->stringSnake2CamelCase($fieldName);
            if ($carbon instanceof \Illuminate\Support\Carbon) {
                $dates[$key] = $carbon->timestamp;
                $dates['local'.ucfirst($key)] = $this->localizeDate($carbon->format('j F Y'), $carbon->locale);
            } else {
                $dates[$key] = null;
                $dates['local'.ucfirst($key)] = null;
            }
        }

        return $dates;
    }

    private function arraySnakeToCamelCase(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) $result[$this->stringSnake2CamelCase($key)] = $value;
        return $result;
    }

    private function stringSnake2CamelCase(string $snakeString, $first = false): string
    {
        $pieces = explode('_', $snakeString);
        $camel = implode('', array_map('ucfirst', $pieces));
        return $first ? $camel : lcfirst($camel);
    }

    private function localizeDate(string $date, string $locale)
    {
        if (!preg_match('/ru/', $locale)) return $date;

        return str_replace([
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ], [
            'января',
            'февраля',
            'марта',
            'апреля',
            'мая',
            'июня',
            'июля',
            'августа',
            'сентября',
            'октября',
            'ноября',
            'декабря',
        ], $date);
    }
}
