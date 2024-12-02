<?php

namespace App\Core\SqlBuilder\CRUD;

use App\Core\SqlBuilder\AbstractBuilder;
use App\Core\SqlBuilder\Traits\BindParamNameTrait;

final class Insert extends AbstractBuilder
{
    use BindParamNameTrait;
    
    private array $columns = [];
    private array $values = [];
    private int $i = 0;
    
    public function values (array ...$values): self
    {
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $this->setValues($value);
                $this->i++;
            } else {
                $this->setValue($key, $value);
            }
        }
        
        return $this;
    }
    
    private function setValues (array $values): void
    {
        foreach ($values as $key => $value) {
            $this->setValue($key, $value);
        }
    }
    
    private function setValue (string $key, string|int|float|bool|null $value): void
    {
        $bindName = $this->getBindParamName();
        $this->values[$this->i][] = $bindName;
        $this->columns[$key] = $key;
        
        $this->setBindParam($bindName, $value);
    }
    
    public function __toString (): string
    {
        $str = "INSERT INTO \n\t" .
            implode(', ', $this->tables) . "\n\t(\n\t\t" .
            implode(",\n\t\t", $this->columns) .
            "\n\t)\nVALUES\n\t";
        
        $params = [];
        
        foreach ($this->values as $value) {
            $params[] = "(\n\t\t" . implode(",\n\t\t", $value) . "\n\t)";
        }
        
        $str .= implode(",\n\t", $params);
        
        
        return $str . ';';
    }
}