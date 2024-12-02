<?php

namespace App\Filters\Api\V1\CRM;

use App\Core\DTO\SortDto;
use App\Core\Filters\AbstractFilter;

final class RoleFilter extends AbstractFilter
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
            SortDto::make('name'),
        ];
    }
    
    protected function sortName (string $direction): void
    {
        $this->builder->orderBy('name', $direction);
    }
}