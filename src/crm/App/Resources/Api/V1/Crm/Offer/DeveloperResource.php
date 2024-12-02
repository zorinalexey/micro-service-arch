<?php

namespace App\Resources\Api\V1\Crm\Offer;

use App\Core\Resources\Common\AbstractResource;

final class DeveloperResource extends AbstractResource
{
    protected function toArray (): array
    {
        return [
            'id' => $this->developer_id,
            'int_id' => $this->developer_int_id,
            'name' => $this->developer_name,
            'links' => $this->links('api/v1/crm/developer', $this->developer_id),
        ];
    }
}