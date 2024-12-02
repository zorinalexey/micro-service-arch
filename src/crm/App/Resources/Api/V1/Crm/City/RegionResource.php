<?php

namespace App\Resources\Api\V1\Crm\City;

use App\Core\Resources\Common\AbstractResource;

final class RegionResource extends AbstractResource
{
    protected function toArray (): array
    {
        return [
            'id' => $this->region_id,
            'int_id' => $this->region_int_id,
            'name' => $this->region_name,
            'links' => $this->links('api/v1/crm/region', $this->region_id),
        ];
    }
}