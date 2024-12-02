<?php

namespace App\Resources\Api\V1\Crm\BlackList;

use App\Core\Resources\Common\AbstractResource;

final class BlackListResource extends AbstractResource
{
    protected function toArray (): array
    {
        return [
            'id' => $this->id,
            'int_id' => $this->int_id,
            'name' => $this->name,
            'links' => $this->links('api/v1/crm/bank', $this->id),
        ];
    }
}