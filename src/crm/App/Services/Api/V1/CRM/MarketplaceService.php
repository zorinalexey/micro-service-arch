<?php

namespace App\Services\Api\V1\CRM;

use App\Core\Service\AbstractService;
use App\Filters\Api\V1\CRM\MarketplaceFilter;
use App\Resources\Api\V1\Crm\Marketplace\MarketplaceResource;

final class MarketplaceService extends AbstractService
{
    protected string $tableName = 'marketplaces';
    protected string $resource = MarketplaceResource::class;
    protected string $filter = MarketplaceFilter::class;
    
    protected array $builderFields = [
        '*',
    ];
    
    protected array $fillable = [
        'name',
    ];
}