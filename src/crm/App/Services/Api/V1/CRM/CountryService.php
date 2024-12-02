<?php

namespace App\Services\Api\V1\CRM;

use App\Core\Service\AbstractService;
use App\Filters\Api\V1\CRM\CountryFilter;
use App\Resources\Api\V1\Crm\Country\CountryResource;

final class CountryService extends AbstractService
{
    protected string $tableName = 'countries';
    protected string $resource = CountryResource::class;
    protected string $filter = CountryFilter::class;
    
    protected array $builderFields = [
        '*',
    ];
    
    protected array $fillable = [
        'name',
    ];
}