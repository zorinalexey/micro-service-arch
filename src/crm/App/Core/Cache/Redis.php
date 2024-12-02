<?php

namespace App\Core\Cache;

use DateMalformedIntervalStringException;
use DateTime;
use Predis\Client;

final class Redis extends AbstractCache implements CacheInterface
{
    private readonly Client $client;
    
    public function __construct (string $host, int $port, string $scheme, string|null $password = null)
    {
        $options = [
            'scheme' => $scheme,
            'host' => $host,
            'port' => $port,
            'password' => $password,
        ];
        $this->client = new Client(parameters : $options);
    }
    
    public function delete (string $key): bool
    {
        return (bool) $this->client->del([$key]);
    }
    
    /**
     * @throws DateMalformedIntervalStringException
     */
    public function remember (string $key, mixed $value, DateTime|null $ttl = null): mixed
    {
        $data = $this->get($key);
        $ttl = $this->getTTL($ttl);
        
        if (!$data) {
            $data = $this->setData($key, $value, $ttl);
            $this->client->set($key, serialize($data), 'EX', $ttl->getTimestamp() - time());
            
            return $data['value'];
        }
        
        return $data;
    }
    
    public function get (string $key): mixed
    {
        if ($value = $this->client->get($key)) {
            return unserialize($value)['value'];
        }
        
        return null;
    }
    
    public function select (int|string|null $path): CacheInterface
    {
        if ($path) {
            $this->client->select($path);
        }
        
        return $this;
    }
    
    public function keys (string $path = '*'): array
    {
        return $this->client->keys($path);
    }
}