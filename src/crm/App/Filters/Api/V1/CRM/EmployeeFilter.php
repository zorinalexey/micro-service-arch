<?php

namespace App\Filters\Api\V1\CRM;

use App\Core\DTO\SortDto;
use App\Core\Filters\AbstractFilter;
use App\Filters\Api\V1\CRM\Traits\LocationFilterTrait;

final class EmployeeFilter extends AbstractFilter
{
    use LocationFilterTrait;
    
    public static function getFilters (): array
    {
        return [
        ];
    }
    
    public static function getSorts (): array
    {
        return [
            SortDto::make('id'),
            SortDto::make('name', 'employee_name.placeholder'),
            SortDto::make('email'),
            SortDto::make('role'),
            SortDto::make('country'),
            SortDto::make('region'),
            SortDto::make('city'),
        ];
    }
    
    
    //TODO: Sorts for employees
    
    protected function sortName (string $direction): void
    {
        $this->builder->orderBy('full_name', $direction);
    }
    
    protected function sortEmail (string $direction): void
    {
        $this->builder->orderBy($this->service->getTableName() . '.email', $direction);
    }
    
    protected function sortRole (string $direction): void
    {
        $this->builder->orderBy('ro.name', $direction);
    }
}