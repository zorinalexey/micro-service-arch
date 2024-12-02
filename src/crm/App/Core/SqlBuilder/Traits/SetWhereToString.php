<?php

namespace App\Core\SqlBuilder\Traits;

trait SetWhereToString
{
    protected function setWhereToString (string &$str): void
    {
        if ($this->where) {
            $params = [];
            
            foreach ($this->where as $where) {
                if (str_contains($where, 'OR')) {
                    array_unshift($params, '(');
                    $params[] = $where;
                    $params[] = ')';
                } else {
                    $params[] = "\t" . $where;
                }
            }
            
            $paramsStr = '';
            $tCount = 0;
            
            foreach ($params as $param) {
                if (str_contains($param, '(')) {
                    $tCount++;
                }
                
                $separator = str_repeat("\t", $tCount);
                $paramsStr .= "\n{$separator}{$param}";
                
                if (str_contains($param, ')')) {
                    $tCount--;
                }
            }
            
            $str .= "\nWHERE " . $paramsStr;
        }
    }
}