<?php

namespace App\Filters\Api\V1\CRM\Traits;

use App\Core\DTO\FilterDto;
use App\Core\Request\Request;
use App\Services\Api\V1\CRM\CityService;
use App\Services\Api\V1\CRM\CountryService;
use App\Services\Api\V1\CRM\RegionService;

trait LocationFilterTrait
{
    protected static function countryFilterDto (): array
    {
        $request = clone(Request::getInstance());
        $request->per_page = 'all';
        unset($request->path);
        $list = (new CountryService())->list($request);
        $options = [];
        
        foreach ($list['data'] as $item) $options[$item['id']] = $item['name'];
        
        return FilterDto::make('country[]', $options, 'dropdown-checkbox', 'country.label', 'country.placeholder');
    }
    
    protected static function regionFilterDto (): array
    {
        $request = clone(Request::getInstance());
        $request->per_page = 'all';
        unset($request->path);
        $list = (new RegionService())->list($request);
        $options = [];
        
        foreach ($list['data'] as $item) $options[$item['id']] = $item['name'];
        
        return FilterDto::make('region[]', $options, 'dropdown-checkbox', 'region.label', 'region.placeholder');
    }
    
    protected static function cityFilterDto (): array
    {
        $request = clone(Request::getInstance());
        $request->per_page = 'all';
        unset($request->path);
        $list = (new CityService())->list($request);
        $options = [];
        
        foreach ($list['data'] as $item) $options[$item['id']] = $item['name'];
        
        return FilterDto::make('city[]', $options, 'dropdown-checkbox', 'city.label', 'city.placeholder');
    }
    
    //TODO: Location filters
    
    protected function city (string|int|array $ids): void
    {
        if ($ids === '') return;
        
        $params = [];
        if (!is_array($ids)) $ids = [$ids];
        
        foreach ($ids as $id) {
            $params = [
                ['c.name', $id],
                ['c.id', $id],
            ];
        }
        
        if (count($params) > 0) {
            $this->builder->where(...$params);
        }
    }
    
    protected function region (string|int|array $ids): void
    {
        if ($ids === '') return;
        
        $params = [];
        if (!is_array($ids)) $ids = [$ids];
        
        foreach ($ids as $id) {
            $params = [
                ['r.name', $id],
                ['r.id', $id],
            ];
        }
        
        if (count($params) > 0) {
            $this->builder->where(...$params);
        }
    }
    
    protected function country (string|int|array $ids): void
    {
        if ($ids === '') return;
        
        $params = [];
        if (!is_array($ids)) $ids = [$ids];
        
        foreach ($ids as $id) {
            $params = [
                ['co.name', $id],
                ['co.id', $id],
            ];
        }
        
        if (count($params) > 0) {
            $this->builder->where(...$params);
        }
    }
    
    //TODO: Location sorts
    
    protected function sortCity (string $direction): void
    {
        $this->builder->orderBy('c.name', $direction);
    }
    
    protected function sortRegion (string $direction): void
    {
        $this->builder->orderBy('r.name', $direction);
    }
    
    protected function sortCountry (string $direction): void
    {
        $this->builder->orderBy('co.name', $direction);
    }
}