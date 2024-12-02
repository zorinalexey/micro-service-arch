<?php

namespace App\Resources\Api\V1\Crm\Role;

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
            'id' => $this->id,
            'int_id' => $this->int_id,
            'name' => $this->name,
            'updated' => (bool) $this->updated,
            'deleted' => (bool) $this->deleted,
            'permissions' => json_decode($this->permissions, false, 512, JSON_THROW_ON_ERROR),
            'default_page' => $this->default_page,
            'links' => $this->links('api/v1/crm/role', $this->id),
        ];
    }
}