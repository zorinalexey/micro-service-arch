<?php

namespace App\Core\SqlBuilder\Traits;

use App\Core\SqlBuilder\CRUD\Enums\Condition;

trait SetConditionTrait
{
    use BindParamNameTrait;
    
    protected function setCondition (Condition $operator, string|int|float|null|bool|array $values): string
    {
        if (is_array($values) && in_array($operator, [Condition::IN, Condition::NOT_IN, Condition::BETWEEN, Condition::NOT_BETWEEN], true)) {
            $str = "{$operator->value} (";
            $params = [];
            
            foreach ($values as $value) {
                $key = $this->getBindParamName();
                $this->setBindParam($key, $value);
                $params[] = $key;
            }
            
            return $str . implode(', ', $params) . ')';
        }
        
        if (is_array($values) && !in_array($operator, [Condition::IN, Condition::NOT_IN, Condition::BETWEEN, Condition::NOT_BETWEEN], true)) {
            $str = Condition::IN->value . " (";
            $params = [];
            
            foreach ($values as $value) {
                $key = $this->getBindParamName();
                $this->setBindParam($key, $value);
                $params[] = $key;
            }
            
            return $str . implode(', ', $params) . ')';
        }
        
        if ($operator === Condition::NOT_CONDITION) {
            return "= {$values}";
        }
        
        if (is_null($values) && in_array($operator, [Condition::IS_NULL, Condition::IS_NOT_NULL], true)) {
            return $operator->value;
        }
        
        if (is_null($values) && !in_array($operator, [Condition::IS_NULL, Condition::IS_NOT_NULL], true)) {
            return Condition::IS_NULL->value;
        }
        
        if (is_bool($values) && in_array($operator, [Condition::IS_TRUE, Condition::IS_FALSE, Condition::IS_NOT_TRUE, Condition::IS_NOT_FALSE], true)) {
            return $operator->value;
        }
        
        if (is_bool($values) && !in_array($operator, [Condition::IS_TRUE, Condition::IS_FALSE, Condition::IS_NOT_TRUE, Condition::IS_NOT_FALSE], true)) {
            if ($values === true) {
                return Condition::IS_TRUE->value;
            }
            
            return Condition::IS_FALSE->value;
        }
        
        if (empty($values) && in_array($operator, [Condition::IS_EMPTY, Condition::IS_NOT_EMPTY], true)) {
            return $operator->value;
        }
        
        if (empty($values) && !in_array($operator, [Condition::IS_EMPTY, Condition::IS_NOT_EMPTY], true)) {
            return Condition::IS_EMPTY->value;
        }
        
        if (is_string($values) && in_array($operator, [Condition::LIKE, Condition::NOT_LIKE, Condition::ILIKE, Condition::NOT_ILIKE], true)) {
            $key = $this->getBindParamName();
            $this->setBindParam($key, $values);
            
            return $operator->value . " {$key}";
        }
        
        $key = $this->getBindParamName();
        $this->setBindParam($key, $values);
        
        return $operator->value . " {$key}";
    }
    
}