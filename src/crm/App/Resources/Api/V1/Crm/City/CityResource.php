<?php

namespace App\Resources\Api\V1\Crm\City;

use App\Core\Resources\Common\AbstractResource;

final class CityResource extends AbstractResource
{
    protected function toArray (): array
    {
        return [
            'id' => $this->id,
            'int_id' => $this->int_id,
            'name' => $this->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'links' => $this->links('api/v1/crm/city', $this->id),
            'region' => RegionResource::make($this->data),
            'country' => CountryResource::make($this->data),
        ];
    }
}