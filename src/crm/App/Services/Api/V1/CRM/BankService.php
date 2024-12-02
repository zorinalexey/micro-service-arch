<?php

namespace App\Services\Api\V1\CRM;

use App\Core\Service\AbstractService;
use App\Filters\Api\V1\CRM\BankFilter;
use App\Resources\Api\V1\Crm\Bank\BankResource;

final class BankService extends AbstractService
{
    protected string $tableName = 'banks';
    protected string $resource = BankResource::class;
    protected string $filter = BankFilter::class;
    
    protected array $builderFields = [
        '*',
    ];
    
    protected array $fillable = [
        'name',
    ];
}