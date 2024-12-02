<?php

namespace App\Filters\Api\V1\CRM;

use App\Core\DTO\SortDto;
use App\Core\Filters\AbstractFilter;

final class CityFilter extends AbstractFilter
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
            SortDto::make('region'),
            SortDto::make('country'),
            SortDto::make('latitude'),
            SortDto::make('longitude'),
        ];
    }
    
    protected function sortName (string $direction): void
    {
        $this->builder->orderBy('name', $direction);
    }
    
    protected function sortRegion (string $direction): void
    {
        $this->builder->orderBy('r.name', $direction);
    }
    
    protected function sortCountry (string $direction): void
    {
        $this->builder->orderBy('co.name', $direction);
    }
    
    protected function sortLatitude (string $direction): void
    {
        $this->builder->orderBy('latitude', $direction);
    }
    
    protected function sortLongitude (string $direction): void
    {
        $this->builder->orderBy('longitude', $direction);
    }
}