<?php

namespace App\Core\SqlBuilder\CRUD;

use App\Core\SqlBuilder\AbstractBuilder;
use App\Core\SqlBuilder\Traits\WhereOrTrait;

final class Update extends AbstractBuilder
{
    use WhereOrTrait;
    
    private array $values = [];
    
    public function values (array $values): self
    {
        foreach ($values as $key => $value) {
            $bindName = $this->getBindParamName();
            $this->values[] = "{$key} = {$bindName}";
            
            $this->setBindParam($bindName, $value);
        }
        
        return $this;
    }
    
    public function __toString (): string
    {
        $str = "UPDATE\n\t" . implode(",\n\t", $this->tables);
        $str .= "\nSET\n\t";
        $str .= implode(",\n\t", $this->values);
        $this->setWhereToString($str);
        
        return $str;
    }
}