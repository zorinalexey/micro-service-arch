<?php

namespace App\Core\Cache;

use DateTime;

interface CacheInterface
{
    public function select (string|int|null $path): self;
    
    public function get (string $key): mixed;
    
    public function delete (string $key): bool;
    
    public function remember (string $key, mixed $value, DateTime $ttl = null): mixed;
    
    public function rememberForever (string $key, mixed $value): mixed;
    
    public function keys (string $path = '*'): array;
}