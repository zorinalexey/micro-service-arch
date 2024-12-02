<?php

namespace App\Core\SqlBuilder\Traits;

use App\Core\SqlBuilder\CRUD\Enums\Condition;

trait GetOperatorTrait
{
    
    protected function getOperator (string|null|Condition $operator): Condition
    {
        if ($operator instanceof Condition) {
            return $operator;
        }
        
        if (is_string($operator)) {
            foreach (Condition::cases() as $case) {
                if ($case->name === $operator || $case->value === $operator) {
                    return $case;
                }
            }
        }
        
        if (is_null($operator)) {
            return Condition::EQUAL;
        }
        
        return $operator;
    }
}