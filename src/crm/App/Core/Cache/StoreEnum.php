<?php

namespace App\Core\Cache;

enum StoreEnum: string
{
    case FILE = 'file';
    case REDIS = 'redis';
    
    public static function get (string $value): self
    {
        return match ($value) {
            'file' => self::FILE,
            'redis' => self::REDIS,
            default => self::FILE,
        };
    }
}
