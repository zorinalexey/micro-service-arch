<?php

namespace App\Resources\Api\V1\Crm\Region;

use App\Core\Resources\Common\AbstractResource;

final class CountryResource extends AbstractResource
{
    protected function toArray (): array
    {
        return [
            'id' => $this->country_id,
            'int_id' => $this->country_int_id,
            'name' => $this->country_name,
            'links' => $this->links('api/v1/crm/country', $this->country_id),
        ];
    }
}