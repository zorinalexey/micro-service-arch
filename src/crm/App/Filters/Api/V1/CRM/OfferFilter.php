<?php

namespace App\Filters\Api\V1\CRM;

use App\Core\DTO\FilterDto;
use App\Core\DTO\SortDto;
use App\Core\Filters\AbstractFilter;
use App\Filters\Api\V1\CRM\Traits\DeveloperFilterTrait;
use App\Filters\Api\V1\CRM\Traits\LocationFilterTrait;
use App\Filters\Api\V1\CRM\Traits\MarketplaceFilterTrait;

final class OfferFilter extends AbstractFilter
{
    use DeveloperFilterTrait, LocationFilterTrait, MarketplaceFilterTrait;
    
    public static function getFilters (): array
    {
        $activeParams = [
            [
                'value' => '',
                'label' => trans('filter', 'all'),
                'default' => 'selected',
            ],
            [
                'value' => true,
                'label' => trans('filter', 'offer.active.label'),
            ],
            [
                'value' => false,
                'label' => trans('filter', 'offer.not_active.label'),
            ],
        ];
        
        return [
            FilterDto::make('name', null, 'input', 'offer.name.label', 'offer.name.placeholder'),
            self::developerFilterDto(),
            self::cityFilterDto(),
            self::marketplaceFilterDto(),
            FilterDto::make('is_active', $activeParams, 'select', 'is_active.label', 'offer.is_active.placeholder'),
            FilterDto::make('limit', null, 'number', 'offer.limit.label', 'offer.limit.placeholder'),
            FilterDto::make('uniqueness_period', null, 'number', 'offer.uniqueness_period.label', 'offer.uniqueness_period.placeholder'),
        ];
    }
    
    public static function getSorts (): array
    {
        return [
            SortDto::make('id'),
            SortDto::make('name'),
            SortDto::make('city'),
            SortDto::make('region'),
            SortDto::make('country'),
            SortDto::make('vertical'),
            SortDto::make('developer'),
            SortDto::make('marketplace'),
            SortDto::make('entity'),
        ];
    }
    
    // TODO: Filters for Offers
    
    protected function uniqueness_period (string|int $period): void
    {
        $this->builder->where(["{$this->service->getTableName()}.uniqueness_period", $period]);
    }
    
    protected function limit (string|int $limit): void
    {
        $this->builder->where(["{$this->service->getTableName()}.limit", $limit]);
    }
    
    protected function name (string|int $name): void
    {
        $this->builder->where(["{$this->service->getTableName()}.name", "%$name%", 'ILIKE']);
    }
    
    protected function developer (string|int|array $ids): void
    {
        if ($ids === '') return;
        
        $params = [];
        if (!is_array($ids)) $ids = [$ids];
        
        foreach ($ids as $id) {
            $params = [
                ['d.id', $id],
                ['d.name', $id],
            ];
        }
        
        if (count($params) > 0) {
            $this->builder->where(...$params);
        }
    }
    
    protected function entity (string|int|array $ids): void
    {
        if ($ids === '') return;
        
        $params = [];
        if (!is_array($ids)) $ids = [$ids];
        
        foreach ($ids as $id) {
            $params = [
                ['entity.id', $id],
                ['entity.name', $id],
            ];
        }
        
        if (count($params) > 0) {
            $this->builder->where(...$params);
        }
    }
   
    
    //TODO: Sorts for offers
    
    protected function sortName (string $direction): void
    {
        $this->builder->orderBy("{$this->service->getTableName()}.name", $direction);
    }
    
    protected function sortVertical (string $direction): void
    {
        $this->builder->orderBy('offers.vertical', $direction);
    }
    
    protected function sortDeveloper (string $direction): void
    {
        $this->builder->orderBy('d.name', $direction);
    }
    
    protected function sortMarketplace (string $direction): void
    {
        $this->builder->orderBy('mp.name', $direction);
    }
    
    protected function sortEntity (string $direction): void
    {
        $this->builder->orderBy('entity.name', $direction);
    }
}