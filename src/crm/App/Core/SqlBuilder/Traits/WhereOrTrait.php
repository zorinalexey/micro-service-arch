<?php

namespace App\Core\SqlBuilder\Traits;

trait WhereOrTrait
{
    use SetConditionTrait, GetOperatorTrait, SetWhereToString;
    
    protected array $where = [];
    
    public function where (array ...$wheres): self
    {
        $str = $this->setWheres($wheres);
        
        if (count($this->where) > 0) {
            $this->where[] = 'AND ' . $str;
        } else {
            $this->where[] = $str;
        }
        
        return $this;
    }
    
    private function setWheres (array $wheres): string
    {
        if (count($wheres) > 1) {
            $params = [];
            
            foreach ($wheres as $where) {
                $params[] = $where[0] . ' ' . $this->setCondition($this->getOperator($where[2] ?? null), $where[1]);
            }
            
            return implode(' OR ', $params);
        }
        
        return $wheres[0][0] . ' ' . $this->setCondition($this->getOperator($wheres[0][2] ?? null), $wheres[0][1]);
    }
    
    public function or (array ...$wheres): self
    {
        array_unshift($this->where, '(');
        $this->where[] = 'OR ' . $this->setWheres($wheres);
        $this->where[] = ')';
        
        return $this;
    }
}