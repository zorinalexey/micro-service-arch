<?php

namespace App\Resources\Api\V1\Crm\Employee;

use App\Core\Resources\Common\AbstractResource;
use JsonException;

final class RoleResource extends AbstractResource
{
    /**
     * @throws JsonException
     */
    protected function toArray (): array
    {
        return [
            'id' => $this->role_id,
            'int_id' => $this->role_int_id,
            'name' => $this->role_name,
            'default_page' => $this->role_default_page,
            'deleted' => $this->role_deleted,
            'updated' => $this->role_updated,
            'permissions' => json_decode($this->permissions, false, 512, JSON_THROW_ON_ERROR),
            'links' => $this->links('api/v1/crm/role', $this->role_id),
        ];
    }
}