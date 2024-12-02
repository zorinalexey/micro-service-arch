<?php

namespace App\Services\Api\V1\CRM\Traits;

use App\Core\SqlBuilder\SqlBuilder;
use App\Services\Api\V1\CRM\RoleService;
use PDO;

trait RoleTrait
{
    protected function getRoles (): array
    {
        $roleBuilder = SqlBuilder::select(['id', 'name'])->table((new RoleService())->getTableName());
        $prepared = db()->getConnection()->prepare($roleBuilder);
        $prepared->execute();
        $roles = [];
        
        foreach ($prepared->fetchAll(PDO::FETCH_ASSOC) as $role) $roles[$role['id']] = $role['name'];
        
        return $roles;
    }
}