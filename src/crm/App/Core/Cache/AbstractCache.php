<?php

namespace App\Core\Cache;

use Closure;
use DateInterval;
use DateMalformedIntervalStringException;
use DateTime;

abstract class AbstractCache
{
    protected array $cache = [];
    
    public function rememberForever (string $key, mixed $value): mixed
    {
        $date = (new DateTime())->add(new DateInterval('P1000Y'));
        
        return $this->remember($key, $value, $date);
    }
    
    protected function setData (string $key, mixed $value, ?DateTime $ttl): array
    {
        $data = [
            'key' => $key,
            'ttl' => $ttl?->format('Y-m-d H:i:s'),
        ];
        
        $data['value'] = $this->setValues($value);
        
        return $data;
    }
    
    private function setValues (mixed $values): mixed
    {
        $data = null;
        
        if ($values instanceof Closure) {
            $data = $values();
        }
        
        if (is_object($values)) {
            $data = $values;
        }
        
        if (is_string($values) || is_int($values) || is_float($values) || is_bool($values) || is_null($values)) {
            $data = $values;
        }
        
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $data[$key] = $this->setValues($value);
            }
        }
        
        return $data;
    }
    
    
    /**
     * @throws DateMalformedIntervalStringException
     */
    protected function getTTL (?DateTime $ttl): DateTime
    {
        if (!$ttl) {
            $config = require CONFIG_PATH . '/cache.php';
            
            $ttl = (new DateTime())->add(new DateInterval('P' . $config['default-time'] . 'D'));
        }
        
        return $ttl;
    }
}