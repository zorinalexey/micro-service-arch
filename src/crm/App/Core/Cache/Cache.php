<?php

namespace App\Core\Cache;

final class Cache
{
    private readonly array $config;
    private CacheInterface|null $store = null;
    
    public function __construct (array $config)
    {
        $this->config = $config;
        $this->store = $this->setStore();
    }
    
    public function getStore (): CacheInterface
    {
        return $this->store;
    }
    
    public function setStore (StoreEnum|null $storeDriver = null): CacheInterface|null
    {
        if (!$storeDriver) {
            $storeDriver = StoreEnum::get($this->config['default-driver']);
        }
        
        $driver = $this->config['driver'][$storeDriver->value] ?? null;
        
        if ($driver) {
            $store = new ($driver['class'])(...$driver['options']);
            $store->select($driver['default-db']);
            
            $this->store = $store;
        }
        
        return $this->store;
    }
}