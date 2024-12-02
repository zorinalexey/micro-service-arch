<?php

namespace App\Resources\Api\V1\Crm\Offer;

use App\Core\Resources\Common\AbstractResource;

final class OfferResource extends AbstractResource
{
    protected function toArray (): array
    {
        return [
            'id' => $this->id,
            'int_id' => $this->int_id,
            'name' => $this->name,
            'vertical' => $this->vertical,
            'is_active' => $this->is_active,
            'expert_mode' => $this->expert_mode,
            'sip_uri' => $this->sip_uri,
            'limit' => (int) $this->limit,
            'uniqueness_period' => (int) $this->uniqueness_period,
            'price' => (float) $this->price,
            'operator_award' => (float) $this->operator_award,
            'not_looking_for_himself' => (bool) $this->not_looking_for_himself,
            'client_is_out_of_town' => (bool) $this->client_is_out_of_town,
            'priority' => (int) $this->priority,
            'external_id' => $this->external_id,
            'call_api_external_id' => $this->call_api_external_id,
            'add_external_number' => $this->add_external_number,
            'developer' => DeveloperResource::make($this->data),
            'city' => CityResource::make($this->data),
            'region' => RegionResource::make($this->data),
            'country' => CountryResource::make($this->data),
            'marketplace' => MarketplaceResource::make($this->data),
            'entity' => EntityResource::make($this->data),
            'links' => $this->links('api/v1/crm/offer', $this->id),
        ];
    }
}