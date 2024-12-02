<?php

namespace App\Filters\Api\V1\CRM\Traits;

use App\Core\DTO\FilterDto;
use App\Core\Request\Request;
use App\Services\Api\V1\CRM\MarketplaceService;

trait MarketplaceFilterTrait
{
    protected static function marketplaceFilterDto (): array
    {
        $request = clone(Request::getInstance());
        $request->per_page = 'all';
        unset($request->path);
        $list = (new MarketplaceService())->list($request);
        $options = [];
        
        
        foreach ($list['data'] as $item) $options[$item['id']] = $item['name'];
        
        return FilterDto::make('marketplace[]', $options, 'dropdown-checkbox', 'marketplace.label', 'marketplace.placeholder');
    }
    
    //TODO: Marketplace filter
    
    protected function marketplace (string|int|array $ids): void
    {
        if ($ids === '') return;
        
        $params = [];
        if (!is_array($ids)) $ids = [$ids];
        
        foreach ($ids as $id) {
            $params = [
                ['mp.name', $id],
                ['mp.id', $id],
            ];
        }
        
        if (count($params) > 0) {
            $this->builder->where(...$params);
        }
    }
}