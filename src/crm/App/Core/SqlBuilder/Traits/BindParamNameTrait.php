<?php

namespace App\Core\SqlBuilder\Traits;

trait BindParamNameTrait
{
    protected function getBindParamName (): string
    {
        return ':bind_' . count($this->binds) + 1;
    }
    
    protected function setBindParam (string $key, mixed $value): void
    {
        if (is_bool($value)) {
            $value = $value ? 1 : 0;
        }
        
        $this->binds[$key] = $value;
    }
}