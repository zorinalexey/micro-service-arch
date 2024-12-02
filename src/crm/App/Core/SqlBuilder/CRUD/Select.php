<?php

namespace App\Core\SqlBuilder\CRUD;

use App\Core\SqlBuilder\AbstractBuilder;
use App\Core\SqlBuilder\CRUD\Enums\Condition;
use App\Core\SqlBuilder\CRUD\Enums\Join;
use App\Core\SqlBuilder\Traits\WhereOrTrait;

final class Select extends AbstractBuilder
{
    use WhereOrTrait;
    
    private array $columns = ['*'];
    private array $joins = [];
    private array $orderBy = [];
    private string|null $sqlToPaginate = null;
    private string|null $limit = null;
    private string|null $offset = null;
    private array $groupBy = [];
    
    public function __construct (array|null $columns = null)
    {
        if ($columns) {
            $this->columns = $columns;
        }
    }
    
    public function join (string $joinTable, string $field1, string|array $field2, Condition|string|null $condition = Condition::NOT_CONDITION, Join $join = Join::LEFT): self
    {
        $this->joins[] = "{$join->value} JOIN\n\t{$joinTable}\nON\n\t{$field1} " . $this->setCondition($condition, $field2);
        
        return $this;
    }
    
    public function __toString (): string
    {
        $str = "SELECT \n\t" .
            implode(", \n\t", $this->columns) .
            "\nFROM \n\t" . implode(",\n\t", $this->tables);
        
        if ($this->joins) {
            $str .= "\n";
            $str .= implode("\n", $this->joins);
        }
        
        $this->setWhereToString($str);
        
        if ($this->groupBy) {
            $str .= "\nGROUP BY \n\t" . implode(",\n\t", $this->groupBy);
        }
        
        if ($this->orderBy) {
            $str .= "\nORDER BY \n\t" . implode(",\n\t", $this->orderBy);
        }
        
        if ($this->limit) {
            $str .= "\n{$this->limit}";
            
            if ($this->offset) {
                $str .= "\n{$this->offset}";
            }
        }
        
        return $str . ';';
    }
    
    public function orderBy (string $column, string $direction = 'ASC'): self
    {
        if (mb_strtoupper($direction) !== 'ASC') {
            $direction = 'DESC';
        } else {
            $direction = 'ASC';
        }
        
        $this->orderBy[] = "{$column} {$direction}";
        
        return $this;
    }
    
    public function paginate (int $limit, int $page = 1): self
    {
        $this->sqlToPaginate = 'SELECT COUNT(*) as total FROM (' . trim((string) $this, ';') . ') AS subquery;';
        $this->limit = "LIMIT {$limit}";
        
        if (($offset = $limit * ($page - 1)) && $offset > 0) {
            $this->offset = "OFFSET {$offset}";
        }
        
        return $this;
    }
    
    public function getPaginateSql (): string
    {
        return $this->sqlToPaginate;
    }
    
    public function groupBy (string|array $columns): self
    {
        if (is_string($columns)) {
            $columns = [$columns];
        }
        
        foreach ($columns as $column) $this->groupBy[] = $column;
        
        return $this;
    }
}