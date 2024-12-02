<?php

namespace App\Services\Api\V1\CRM;

use App\Core\Service\AbstractService;
use App\Filters\Api\V1\CRM\DeveloperFilter;
use App\Resources\Api\V1\Crm\Developer\DeveloperResource;

final class DeveloperService extends AbstractService
{
    protected string $tableName = 'developers';
    protected string $resource = DeveloperResource::class;
    protected string $filter = DeveloperFilter::class;
    
    protected array $builderFields = [
        '*',
    ];
    
    protected array $fillable = [
        'name',
    ];
}