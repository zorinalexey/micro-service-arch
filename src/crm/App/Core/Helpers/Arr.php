<?php

namespace App\Core\Helpers;

final class Arr
{
    public static function pull (array &$arr, string $key, mixed $default = null): mixed
    {
        
        
        $data = self::get($arr, $key, self::setDefault($default));
        self::delete($arr, $key);
        
        return $data;
    }
    
    public static function get (array $arr, string $key, mixed $default = null): mixed
    {
        return $arr[$key] ?? $default;
    }
    
    private static function setDefault (mixed $default): mixed
    {
        if (is_callable($default)) {
            return $default();
        }
        
        return $default;
    }
    
    public static function delete (array &$arr, string $key): void
    {
        unset($arr[$key]);
    }
    
    public static function push (array &$arr, string $key, mixed $value, bool $inHead = false): array
    {
        if ($inHead) {
            array_unshift($arr, $value);
        } else {
            self::set($arr, $key, $value);
        }
        
        return $arr;
    }
    
    public static function set (array &$arr, string $key, mixed $value): void
    {
        $arr[$key] = $value;
    }
    
    public static function has (array $arr, string $key): bool
    {
        return array_key_exists($key, $arr);
    }
    
    public static function keys (array $arr): array
    {
        return array_keys($arr);
    }
    
    public static function values (array $arr): array
    {
        return array_values($arr);
    }
    
    public static function count (array $arr): int
    {
        return count($arr);
    }
    
    public static function isAssocAndNotEmpty (array $arr): bool
    {
        return self::isAssoc($arr) && self::isNotEmpty($arr);
    }
    
    public static function isAssoc (array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
    
    public static function isNotEmpty (array $arr): bool
    {
        return !empty($arr);
    }
    
    public static function isAssocAndNotNotEmpty (array $arr): bool
    {
        return self::isAssoc($arr) && self::isEmpty($arr);
    }
    
    public static function isEmpty (array $arr): bool
    {
        return empty($arr);
    }
    
    public static function isAssocOrNotEmpty (array $arr): bool
    {
        return self::isAssoc($arr) || self::isNotEmpty($arr);
    }
    
    public static function isAssocOrNotAssoc (array $arr): bool
    {
        return self::isAssoc($arr) || self::isNotAssoc($arr);
    }
    
    public static function isNotAssoc (array $arr): bool
    {
        return !self::isAssoc($arr);
    }
}