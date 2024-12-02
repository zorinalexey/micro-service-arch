<?php

namespace App\Core\SqlBuilder\CRUD;

use App\Core\SqlBuilder\AbstractBuilder;
use App\Core\SqlBuilder\Traits\WhereOrTrait;

final class Delete extends AbstractBuilder
{
    use WhereOrTrait;
    
    public function __toString (): string
    {
        $str = "DELETE FROM\n\t" . implode("\n\t", $this->tables);
        $this->setWhereToString($str);
        
        return $str . ';';
    }
}