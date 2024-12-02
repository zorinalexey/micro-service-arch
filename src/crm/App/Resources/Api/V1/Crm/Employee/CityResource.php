<?php

namespace App\Resources\Api\V1\Crm\Employee;

use App\Core\Resources\Common\AbstractResource;

final class CityResource extends AbstractResource
{
    protected function toArray (): array
    {
        return [
            'id' => $this->city_id,
            'int_id' => $this->city_int_id,
            'name' => $this->city_name,
            'links' => $this->links('api/v1/crm/city', $this->city_id),
        ];
    }
}