<?php

namespace Lox;

abstract class Enum {
    // cache instance
    protected static $instance = [];

    // get list of all constant
    public static function getNames(): array {
        return (new \ReflectionClass(static::class))->getConstants();
    }

    // get name of an instance
    public static function getName(self $value): string {
        if (in_array($value, static::$instance)) {
            $name = array_search($value, static::$instance, true);
            if (strpos($name, static::class) === 0)
                return array_slice(explode('\\', $name), -1)[0];
        }
        throw new \InvalidArgumentException('Gived argument is not an instance of ' . static::class);
    }

    // get value name of an instance
    public static function getValue(self $value): string {
        $name = self::getName($value);
        return (new \ReflectionClass(static::class))->getConstant($name);
    }

    // overload static call for create value
    public static function __callStatic($name, $arguments): self {
        $realName = static::class . '\\' . $name;
        if (array_key_exists($realName, static::$instance))
            return static::$instance[$realName];
    
        if (array_key_exists($name, self::getNames()));
            return static::$instance[$realName] = new static();
        
        throw new \BadMethodCallException($name . ' not in enum ' . static::class);
    }
}