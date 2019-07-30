<?php /** @noinspection PhpUnused */

namespace WofhTools\Core\Forms;


/**
 * Class Assert
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2015–2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Core
 */
class Assert
{
    //==
    //== Type Assertions
    //== ======================================= ==//

    public static function isString($value, $message = '')
    {
        \Webmozart\Assert\Assert::string($value, $message);
    }


    public static function isStringNotEmpty($value, $message = '')
    {
        \Webmozart\Assert\Assert::stringNotEmpty($value, $message);
    }


    public static function isInteger($value, $message = '')
    {
        \Webmozart\Assert\Assert::integer($value, $message);
    }


    public static function isIntegerish($value, $message = '')
    {
        \Webmozart\Assert\Assert::integerish($value, $message);
    }


    public static function isFloat($value, $message = '')
    {
        \Webmozart\Assert\Assert::float($value, $message);
    }


    public static function isNumeric($value, $message = '')
    {
        \Webmozart\Assert\Assert::numeric($value, $message);
    }


    public static function isNatural($value, $message = '')
    {
        \Webmozart\Assert\Assert::natural($value, $message);
    }


    public static function isBoolean($value, $message = '')
    {
        \Webmozart\Assert\Assert::boolean($value, $message);
    }


    public static function isScalar($value, $message = '')
    {
        \Webmozart\Assert\Assert::scalar($value, $message);
    }


    public static function isObject($value, $message = '')
    {
        \Webmozart\Assert\Assert::object($value, $message);
    }


    public static function isResource($value, $type = null, $message = '')
    {
        \Webmozart\Assert\Assert::resource($value, $type, $message);
    }


    public static function isCallable($value, $message = '')
    {
        \Webmozart\Assert\Assert::isCallable($value, $message);
    }


    public static function isArray($value, $message = '')
    {
        \Webmozart\Assert\Assert::isArray($value, $message);
    }


    public static function isArrayAccessible($value, $message = '')
    {
        \Webmozart\Assert\Assert::isArrayAccessible($value, $message);
    }


    public static function isIterable($value, $message = '')
    {
        \Webmozart\Assert\Assert::isIterable($value, $message);
    }


    public static function isCountable($value, $message = '')
    {
        \Webmozart\Assert\Assert::isCountable($value, $message);
    }


    public static function isInstanceOf($value, $class, $message = '')
    {
        \Webmozart\Assert\Assert::isInstanceOf($value, $class, $message);
    }


    public static function notInstanceOf($value, $class, $message = '')
    {
        \Webmozart\Assert\Assert::notInstanceOf($value, $class, $message);
    }


    public static function isInstanceOfAny($value, array $classes, $message = '')
    {
        \Webmozart\Assert\Assert::isInstanceOfAny($value, $classes, $message);
    }

    //==
    //== Comparison Assertions
    //== ======================================= ==//

    public static function true($value, $message = '')
    {
        \Webmozart\Assert\Assert::true($value, $message);
    }


    public static function false($value, $message = '')
    {
        \Webmozart\Assert\Assert::false($value, $message);
    }


    public static function null($value, $message = '')
    {
        \Webmozart\Assert\Assert::null($value, $message);
    }


    public static function notNull($value, $message = '')
    {
        \Webmozart\Assert\Assert::notNull($value, $message);
    }


    public static function isEmpty($value, $message = '')
    {
        \Webmozart\Assert\Assert::isEmpty($value, $message);
    }


    public static function notEmpty($message = '')
    {
        return function ($value) use ($message) {
            \Webmozart\Assert\Assert::notEmpty($value, $message);
        };
    }


    public static function eq($value, $value2, $message = '')
    {
        \Webmozart\Assert\Assert::eq($value, $value2, $message);
    }


    public static function notEq($value, $value2, $message = '')
    {
        \Webmozart\Assert\Assert::notEq($value, $value2, $message);
    }


    public static function same($value, $value2, $message = '')
    {
        \Webmozart\Assert\Assert::same($value, $value2, $message);
    }


    public static function notSame($value, $value2, $message = '')
    {
        \Webmozart\Assert\Assert::notSame($value, $value2, $message);
    }


    public static function greaterThan($value, $limit, $message = '')
    {
        \Webmozart\Assert\Assert::greaterThan($value, $limit, $message);
    }


    public static function greaterThanEq($value, $limit, $message = '')
    {
        \Webmozart\Assert\Assert::greaterThanEq($value, $limit, $message);
    }


    public static function lessThan($value, $limit, $message = '')
    {
        \Webmozart\Assert\Assert::lessThan($value, $limit, $message);
    }


    public static function lessThanEq($value, $limit, $message = '')
    {
        \Webmozart\Assert\Assert::lessThanEq($value, $limit, $message);
    }


    public static function range($value, $min, $max, $message = '')
    {
        \Webmozart\Assert\Assert::range($value, $min, $max, $message);
    }


    public static function oneOf($value, array $values, $message = '')
    {
        \Webmozart\Assert\Assert::oneOf($value, $values, $message);
    }

    //==
    //== String Assertions
    //== ======================================= ==//

    public static function contains($value, $subString, $message = '')
    {
        \Webmozart\Assert\Assert::contains($value, $subString, $message);
    }


    public static function notContains($value, $subString, $message = '')
    {
        \Webmozart\Assert\Assert::notContains($value, $subString, $message);
    }


    public static function startsWith($value, $prefix, $message = '')
    {
        \Webmozart\Assert\Assert::startsWith($value, $prefix, $message);
    }


    public static function startsWithLetter($value, $message = '')
    {
        \Webmozart\Assert\Assert::startsWithLetter($value, $message);
    }


    public static function endsWith($value, $suffix, $message = '')
    {
        \Webmozart\Assert\Assert::endsWith($value, $suffix, $message);
    }


    public static function regex($value, $pattern, $message = '')
    {
        \Webmozart\Assert\Assert::regex($value, $pattern, $message);
    }


    public static function notRegex($value, $pattern, $message = '')
    {
        \Webmozart\Assert\Assert::notRegex($value, $pattern, $message);
    }


    public static function alpha($value, $message = '')
    {
        \Webmozart\Assert\Assert::alpha($value, $message);
    }


    public static function digits($value, $message = '')
    {
        \Webmozart\Assert\Assert::digits($value, $message);
    }


    public static function alnum($value, $message = '')
    {
        \Webmozart\Assert\Assert::alnum($value, $message);
    }


    public static function lower($value, $message = '')
    {
        \Webmozart\Assert\Assert::lower($value, $message);
    }


    public static function upper($value, $message = '')
    {
        \Webmozart\Assert\Assert::upper($value, $message);
    }


    public static function length($value, $length, $message = '')
    {
        \Webmozart\Assert\Assert::length($value, $length, $message);
    }


    public static function minLength($value, $min, $message = '')
    {
        \Webmozart\Assert\Assert::minLength($value, $min, $message);
    }


    public static function maxLength($value, $max, $message = '')
    {
        \Webmozart\Assert\Assert::maxLength($value, $max, $message);
    }


    public static function lengthBetween($value, $min, $max, $message = '')
    {
        \Webmozart\Assert\Assert::lengthBetween($value, $min, $max, $message);
    }


    public static function uuid($value, $message = '')
    {
        \Webmozart\Assert\Assert::uuid($value, $message);
    }


    public static function ip($value, $message = '')
    {
        \Webmozart\Assert\Assert::ip($value, $message);
    }


    public static function ipv4($value, $message = '')
    {
        \Webmozart\Assert\Assert::ipv4($value, $message);
    }


    public static function ipv6($value, $message = '')
    {
        \Webmozart\Assert\Assert::ipv6($value, $message);
    }


    public static function notWhitespaceOnly($value, $message = '')
    {
        \Webmozart\Assert\Assert::notWhitespaceOnly($value, $message);
    }

    //==
    //== File Assertions
    //== ======================================= ==//

    public static function fileExists($value, $message = '')
    {
        \Webmozart\Assert\Assert::fileExists($value, $message);
    }


    public static function file($value, $message = '')
    {
        \Webmozart\Assert\Assert::file($value, $message);
    }


    public static function directory($value, $message = '')
    {
        \Webmozart\Assert\Assert::directory($value, $message);
    }


    public static function readable($value, $message = '')
    {
        \Webmozart\Assert\Assert::readable($value, $message);
    }


    public static function writable($value, $message = '')
    {
        \Webmozart\Assert\Assert::writable($value, $message);
    }

    //==
    //== Object Assertions
    //== ======================================= ==//

    public static function classExists($value, $message = '')
    {
        \Webmozart\Assert\Assert::classExists($value, $message);
    }


    public static function subclassOf($value, $class, $message = '')
    {
        \Webmozart\Assert\Assert::subclassOf($value, $class, $message);
    }


    public static function interfaceExists($value, $message = '')
    {
        \Webmozart\Assert\Assert::interfaceExists($value, $message);
    }


    public static function implementsInterface($value, $interface, $message = '')
    {
        \Webmozart\Assert\Assert::implementsInterface($value, $interface, $message);
    }


    public static function propertyExists($classOrObject, $property, $message = '')
    {
        \Webmozart\Assert\Assert::propertyExists($classOrObject, $property, $message);
    }


    public static function propertyNotExists($classOrObject, $property, $message = '')
    {
        \Webmozart\Assert\Assert::propertyNotExists($classOrObject, $property, $message);
    }


    public static function methodExists($classOrObject, $method, $message = '')
    {
        \Webmozart\Assert\Assert::methodExists($classOrObject, $method, $message);
    }


    public static function methodNotExists($classOrObject, $method, $message = '')
    {
        \Webmozart\Assert\Assert::methodNotExists($classOrObject, $method, $message);
    }

    //==
    //== Array Assertions
    //== ======================================= ==//

    public static function keyExists($array, $key, $message = '')
    {
        \Webmozart\Assert\Assert::keyExists($array, $key, $message);
    }


    public static function keyNotExists($array, $key, $message = '')
    {
        \Webmozart\Assert\Assert::keyNotExists($array, $key, $message);
    }


    public static function count($array, $number, $message = '')
    {
        \Webmozart\Assert\Assert::count($array, $number, $message);
    }


    public static function minCount($array, $min, $message = '')
    {
        \Webmozart\Assert\Assert::minCount($array, $min, $message);
    }


    public static function maxCount($array, $max, $message = '')
    {
        \Webmozart\Assert\Assert::maxCount($array, $max, $message);
    }


    public static function countBetween($array, $min, $max, $message = '')
    {
        \Webmozart\Assert\Assert::countBetween($array, $min, $max, $message);
    }


    public static function isList($array, $message = '')
    {
        \Webmozart\Assert\Assert::isList($array, $message);
    }


    public static function isMap($array, $message = '')
    {
        \Webmozart\Assert\Assert::isMap($array, $message);
    }

    //==
    //== Other Assertions
    //== ======================================= ==//

    public static function isEmail($message = '')
    {
        return function ($value) use ($message) {
            \Webmozart\Assert\Assert::notEmpty($value, $message);
            $value = filter_var($value, FILTER_VALIDATE_EMAIL);

            if ($value === null || $value === false) {
                throw new \InvalidArgumentException(sprintf(
                    $message ?: 'Invalid e-mail address. Got: %s',
                    static::valueToString($value)
                ));
            }
        };
    }

    //==
    //== Service methods
    //== ======================================= ==//

    protected static function valueToString($value)
    {
        if (null === $value) {
            return 'null';
        }

        if (true === $value) {
            return 'true';
        }

        if (false === $value) {
            return 'false';
        }

        if (is_array($value)) {
            return 'array';
        }

        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return get_class($value).': '.self::valueToString($value->__toString());
            }

            return get_class($value);
        }

        if (is_resource($value)) {
            return 'resource';
        }

        if (is_string($value)) {
            return '"'.$value.'"';
        }

        return (string)$value;
    }
}
