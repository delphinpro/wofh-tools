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
     * @param string[] $fieldNames
     * @return array
     */
    private function dateFieldsAsTimestamps(array $fieldNames): array
    {
        $dates = [];
        foreach ($fieldNames as $fieldName) {
            /** @var \Carbon\Carbon $carbon */
            $carbon = $this->{$fieldName};
            if ($carbon instanceof \Illuminate\Support\Carbon) {
                $dates[$this->stringSnake2CamelCase($fieldName)] = $carbon->timestamp;
            } else {
                $dates[$this->stringSnake2CamelCase($fieldName)] = null;
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
}
