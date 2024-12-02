<?php

namespace App\Resources\Api\V1\Crm\Offer;

use App\Core\Resources\Common\AbstractResource;

final class MarketplaceResource extends AbstractResource
{
    protected function toArray (): array
    {
        return [
            'id' => $this->marketplace_id,
            'int_id' => $this->marketplace_int_id,
            'name' => $this->marketplace_name,
            'links' => $this->links('api/v1/crm/marketplace', $this->marketplace_id),
        ];
    }
}