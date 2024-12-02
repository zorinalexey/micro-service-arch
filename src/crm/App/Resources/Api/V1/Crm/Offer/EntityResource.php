<?php

namespace App\Resources\Api\V1\Crm\Offer;

use App\Core\Resources\Common\AbstractResource;

final class EntityResource extends AbstractResource
{
    protected function toArray (): array
    {
        $entity = match ($this->vertical) {
            default => 'real_estate_building',
            'auto' => 'auto',
            'cian' => 'cian',
        };
        
        return [
            'id' => $this->entity_id,
            'int_id' => $this->entity_int_id,
            'name' => $this->entity_name,
            'links' => $this->links("api/v1/crm/{$entity}", $this->city_id),
        ];
    }
}