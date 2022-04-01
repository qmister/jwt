<?php

namespace Whereof\Jwt\Support;

use ArrayAccess;
use Closure;
use Exception;
use InvalidArgumentException;

/**
 * Class Arr
 * @author zhiqiang
 * @package Whereof\Jwt\Support
 */
class Arr
{
    /**
     * @param $array
     * @param $path
     * @param $value
     */
    public static function setValue(&$array, $path, $value)
    {
        if ($path === null) {
            $array = $value;
            return;
        }
        $keys = is_array($path) ? $path : explode('.', $path);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key])) {
                $array[$key] = [];
            }
            if (!is_array($array[$key])) {
                $array[$key] = [$array[$key]];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
    }

    /**
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed
     * @throws Exception
     */
    public static function getValue($array, $key, $default = null)
    {
        if ($key instanceof Closure) {
            return $key($array, $default);
        }
        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }
        if (is_object($array) && property_exists($array, $key)) {
            return $array->$key;
        }
        if (static::keyExists($key, $array)) {
            return $array[$key];
        }
        if (($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }
        if (static::keyExists($key, $array)) {
            return $array[$key];
        }
        if (is_object($array)) {
            // this is expected to fail if the property does not exist, or __get() is not implemented
            // it is not reliably possible to check whether a property is accessible beforehand
            try {
                return $array->$key;
            } catch (Exception $e) {
                if ($array instanceof ArrayAccess) {
                    return $default;
                }
                throw $e;
            }
        }
        return $default;
    }

    /**
     * @param $array
     * @param $name
     * @param bool $keepKeys
     * @return array
     * @throws Exception
     */
    public static function getColumn($array, $name, $keepKeys = true)
    {
        $result = [];
        if ($keepKeys) {
            foreach ($array as $k => $element) {
                $result[$k] = static::getValue($element, $name);
            }
        } else {
            foreach ($array as $element) {
                $result[] = static::getValue($element, $name);
            }
        }
        return $result;
    }

    /**
     * @param $key
     * @param $array
     * @param bool $caseSensitive
     *
     * @return bool
     */
    public static function keyExists($key, $array, $caseSensitive = true)
    {
        if ($caseSensitive) {
            // Function `isset` checks key faster but skips `null`, `array_key_exists` handles this case
            // https://secure.php.net/manual/en/function.array-key-exists.php#107786
            if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
                return true;
            }
            // Cannot use `array_has_key` on Objects for PHP 7.4+, therefore we need to check using [[ArrayAccess::offsetExists()]]
            return $array instanceof ArrayAccess && $array->offsetExists($key);
        }
        if ($array instanceof ArrayAccess) {
            throw new InvalidArgumentException('Second parameter($array) cannot be ArrayAccess in case insensitive mode');
        }
        foreach (array_keys($array) as $k) {
            if (strcasecmp($key, $k) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $array
     * @param $key
     * @param array $groups
     * @return array
     * @throws Exception
     */
    public static function index($array, $key, $groups = [])
    {
        $result = [];
        $groups = (array)$groups;
        foreach ($array as $element) {
            $lastArray = &$result;
            foreach ($groups as $group) {
                $value = static::getValue($element, $group);
                if (!array_key_exists($value, $lastArray)) {
                    $lastArray[$value] = [];
                }
                $lastArray = &$lastArray[$value];
            }
            if ($key === null) {
                if (!empty($groups)) {
                    $lastArray[] = $element;
                }
            } else {
                $value = static::getValue($element, $key);
                if ($value !== null) {
                    if (is_float($value)) {
                        $value = str_replace(',', '.', (string)$value);
                    }
                    $lastArray[$value] = $element;
                }
            }
            unset($lastArray);
        }

        return $result;
    }

    /**
     * @param $array
     * @param $from
     * @param $to
     * @param null $group
     * @return array
     * @throws Exception
     */
    public static function map($array, $from, $to, $group = null)
    {
        $result = [];
        foreach ($array as $element) {
            $key = static::getValue($element, $from);
            $value = static::getValue($element, $to);
            if ($group !== null) {
                $result[static::getValue($element, $group)][$key] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public static function remove(&$array, $key, $default = null)
    {
        if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
            $value = $array[$key];
            unset($array[$key]);
            return $value;
        }
        return $default;
    }
}