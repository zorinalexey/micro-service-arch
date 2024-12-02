<?php

namespace App\Services\Api\V1\CRM;

use App\Core\Service\AbstractService;
use App\Filters\Api\V1\CRM\RoleFilter;
use App\Resources\Api\V1\Crm\Role\RoleResource;

final class RoleService extends AbstractService
{
    protected string $tableName = 'roles';
    protected string $resource = RoleResource::class;
    protected string $filter = RoleFilter::class;
    
    protected array $builderFields = [
        '*',
    ];
    
    protected array $fillable = [
        'name',
        'deleted',
        'updated',
        'permissions',
        'default_page',
    ];
}