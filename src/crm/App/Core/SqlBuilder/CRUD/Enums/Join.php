<?php

namespace App\Core\SqlBuilder\CRUD\Enums;

enum Join: string
{
    case INNER = 'INNER';
    case LEFT = 'LEFT';
    case RIGHT = 'RIGHT';
    case FULL_OUTER = 'FULL OUTER';
    case CROSS = 'CROSS';
}
