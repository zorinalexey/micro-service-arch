<?php

namespace App\Core\SqlBuilder\CRUD\Enums;

enum Condition: string
{
    case EQUAL = '=';
    case NOT_EQUAL = '<>';
    case GREATER_THAN = '>';
    case LESS_THAN = '<';
    case GREATER_THAN_OR_EQUAL = '>=';
    case LESS_THAN_OR_EQUAL = '<=';
    case IN = 'IN';
    case NOT_IN = 'NOT IN';
    case LIKE = 'LIKE';
    case NOT_LIKE = 'NOT LIKE';
    case ILIKE = 'ILIKE';
    case NOT_ILIKE = 'NOT ILIKE';
    case BETWEEN = 'BETWEEN';
    case NOT_BETWEEN = 'NOT BETWEEN';
    case IS_NULL = 'IS NULL';
    case IS_NOT_NULL = 'IS NOT NULL';
    case IS_TRUE = 'IS TRUE';
    case IS_FALSE = 'IS FALSE';
    case IS_NOT_TRUE = 'IS NOT TRUE';
    case IS_NOT_FALSE = 'IS NOT FALSE';
    case IS_EMPTY = 'IS EMPTY';
    case IS_NOT_EMPTY = 'IS NOT EMPTY';
    case NOT_CONDITION = '';
}
