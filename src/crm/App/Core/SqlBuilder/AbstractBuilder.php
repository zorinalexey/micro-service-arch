<?php

namespace App\Core\SqlBuilder;

abstract class AbstractBuilder
{
    protected array $tables = [];
    protected array $binds = [];
    
    public function table (array|string $table): self
    {
        if (is_string($table)) {
            $table = [$table];
        }
        
        foreach ($table as $t) $this->tables[] = $t;
        
        return $this;
    }
    
    public function getBinds (): array
    {
        return $this->binds;
    }
}