<?php

namespace Travelhood\Library\Provider\GoGlobal;

use SplEnum;
use ReflectionClass;

abstract class EnumAbstract extends SplEnum
{
    private static $_constants = null;
    private static function getConstants() {
        if (!self::$_constants) {
            $reflect = new ReflectionClass(get_called_class());
            self::$_constants = $reflect->getConstants();
        }
        return self::$_constants;
    }
    public static function isValidName($name, $strict=false) {
        $constants = self::getConstants();
        if ($strict) {
            return array_key_exists($name, $constants);
        }
        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }
    public static function isValidValue($value) {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict=true);
    }
    public static function getString($value) {
        return array_search($value, self::getConstants());
    }
}
