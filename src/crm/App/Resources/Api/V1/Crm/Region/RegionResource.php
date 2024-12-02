<?php

namespace App\Resources\Api\V1\Crm\Region;

use App\Core\Resources\Common\AbstractResource;

final class RegionResource extends AbstractResource
{
    protected function toArray (): array
    {
        return [
            'id' => $this->id,
            'int_id' => $this->int_id,
            'name' => $this->name,
            'links' => $this->links('api/v1/crm/region', $this->id),
            'country' => CountryResource::make($this->data),
        ];
    }
}