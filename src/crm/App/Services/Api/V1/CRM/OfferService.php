<?php

namespace App\Services\Api\V1\CRM;

use App\Core\Service\AbstractService;
use App\Core\SqlBuilder\CRUD\Select;
use App\Core\SqlBuilder\SqlBuilder;
use App\Filters\Api\V1\CRM\OfferFilter;
use App\Resources\Api\V1\Crm\Offer\OfferResource;

final class OfferService extends AbstractService
{
    protected string $tableName = 'offers';
    protected string $resource = OfferResource::class;
    protected string $filter = OfferFilter::class;
    
    protected array $builderFields = [
        'offers.id AS id',
        'offers.int_id AS int_id',
        'offers.name AS name',
        'offers.vertical AS vertical',
        'offers.developer_id AS developer_id',
        'offers.marketplace_id AS marketplace_id',
        'offers.city_id AS city_id',
        'offers.region_id AS region_id',
        'offers.country_id AS country_id',
        'offers.real_estate_building_id AS entity_id',
        'offers.is_active AS is_active',
        'offers.expert_mode AS expert_mode',
        'offers.sip_uri AS sip_uri',
        'offers.limit AS limit',
        'offers.uniqueness_period AS unique_period',
        'offers.price AS price',
        'offers.operator_award AS operator_award',
        'offers.not_looking_for_himself AS not_looking_for_himself',
        'offers.client_is_out_of_town AS client_is_out_of_town',
        'offers.priority AS priority',
        'offers.external_id AS external_id',
        'd.id AS developer_id',
        'd.int_id AS developer_int_id',
        'd.name AS developer_name',
        'c.id AS city_id',
        'c.int_id AS city_int_id',
        'c.name AS city_name',
        'r.id AS region_id',
        'r.int_id AS region_int_id',
        'r.name AS region_name',
        'co.id AS country_id',
        'co.int_id AS country_int_id',
        'co.name AS country_name',
        'mp.id AS marketplace_id',
        'mp.int_id AS marketplace_int_id',
        'mp.name AS marketplace_name',
        'entity.id AS entity_id',
        'entity.int_id AS entity_int_id',
        'entity.name AS entity_name',
    ];
    
    protected array $fillable = [
        'name',
        'vertical',
        'developer_id',
        'marketplace_id',
        'city_id',
        'region_id',
        'country_id',
        'real_estate_building_id',
        'is_active',
        'expert_mode',
        'sip_uri',
        'limit',
        'uniqueness_period',
        'price',
        'operator_award',
        'not_looking_for_himself',
        'client_is_out_of_town',
        'priority',
        'external_id',
        'call_api_external_id',
        'add_external_number',
    ];
    
    protected function getBuilder (array $data = []): Select
    {
        $builder = SqlBuilder::select($this->getBuilderFields())->table($this->getTableName())
            ->join('developers AS d', 'd.id', 'offers.developer_id')
            ->join('cities AS c', 'c.id', 'offers.city_id')
            ->join('regions AS r', 'r.id', 'offers.region_id')
            ->join('countries AS co', 'co.id', 'offers.country_id')
            ->join('marketplaces AS mp', 'mp.id', 'offers.marketplace_id')
            ->join("LATERAL (\n\t\t" .
                "SELECT * FROM real_estate_buildings WHERE offers.vertical = 'REALTY' UNION ALL\n\t\t" .
                "SELECT * FROM real_estate_buildings WHERE offers.vertical = 'AUTO' UNION ALL\n\t\t" .
                "SELECT * FROM real_estate_buildings WHERE offers.vertical = 'CIAN'\n\t\t" .
                "\n\t) AS entity", 'entity.id', 'offers.real_estate_building_id')
            ->groupBy([
                'offers.id', 'offers.int_id', 'offers.name', 'offers.vertical', 'offers.developer_id', 'offers.marketplace_id',
                'offers.city_id', 'offers.region_id', 'offers.country_id', 'offers.real_estate_building_id', 'offers.is_active',
                'offers.expert_mode', 'offers.sip_uri', 'offers.limit', 'offers.uniqueness_period', 'offers.price',
                'offers.operator_award', 'offers.not_looking_for_himself', 'offers.client_is_out_of_town',
                'offers.priority', 'offers.external_id', 'offers.call_api_external_id', 'd.id', 'd.int_id', 'd.name',
                'c.id', 'c.int_id', 'c.name', 'r.id', 'r.int_id', 'r.name', 'co.id', 'co.int_id', 'co.name',
                'mp.id', 'mp.int_id', 'mp.name', 'entity.id', 'entity.int_id', 'entity.name',
            ])
            ->where(['entity.id', null, 'IS NOT NULL']);
        $this->filter::filter($builder, $this, $data);
        
        return $builder;
    }
}