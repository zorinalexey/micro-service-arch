<?php

namespace App\Filters\Api\V1\CRM;

use App\Core\DTO\SortDto;
use App\Core\Filters\AbstractFilter;

final class BlackListFilter extends AbstractFilter
{
    public static function getFilters (): array
    {
        return [
        ];
    }
    
    public static function getSorts (): array
    {
        return [
            SortDto::make('id'),
        ];
    }
    
}