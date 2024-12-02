<?php

namespace App\Core\Cache;

use DateTime;

final class File extends AbstractCache implements CacheInterface
{
    public function __construct (string $path)
    {
    
    }
    
    public function get (string $key): mixed
    {
        return null;
    }
    
    
    public function delete (string $key): bool
    {
        return false;
    }
    
    public function remember (string $key, mixed $value, DateTime $ttl = null): mixed
    {
        return null;
    }
    
    public function select (int|string|null $path): CacheInterface
    {
        return $this;
    }
}