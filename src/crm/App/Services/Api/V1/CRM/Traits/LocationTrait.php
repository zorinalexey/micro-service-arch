<?php

namespace App\Services\Api\V1\CRM\Traits;

use App\Core\SqlBuilder\SqlBuilder;
use App\Services\Api\V1\CRM\CityService;
use App\Services\Api\V1\CRM\CountryService;
use App\Services\Api\V1\CRM\RegionService;
use PDO;

trait LocationTrait
{
    protected function getLocations (bool $withCountry = true, bool $withRegion = true, bool $withCity = true): array
    {
        $countryTable = (new CountryService())->getTableName();
        $regionTable = (new RegionService())->getTableName();
        $cityTable = (new CityService())->getTableName();
        $addressBuilder = SqlBuilder::select([
            "{$countryTable}.id AS country_id",
            "{$countryTable}.name AS country_name",
            'r.name AS region_name',
            'r.id AS region_id',
            'c.name AS city_name',
            'c.id AS city_id',
        ])->table($countryTable)
            ->join("{$regionTable} AS r", 'r.country_id', "{$countryTable}.id")
            ->join("{$cityTable} AS c", 'c.region_id', 'r.id');
        
        $prepared = db()->getConnection()->prepare($addressBuilder);
        $prepared->execute();
        $countries = [];
        $regions = [];
        $cities = [];
        
        foreach ($prepared->fetchAll(PDO::FETCH_ASSOC) as $address) {
            if ($withCountry) {
                $countries[$address['country_id']] = $address['country_name'];
            }
            
            if ($withRegion) {
                $regions[$address['country_id']][$address['region_id']] = $address['region_name'];
            }
            
            if ($withCity) {
                $cities[$address['country_id']][$address['region_id']][$address['city_id']] = $address['city_name'];
                $cities[$address['country_id']][$address['city_id']] = $address['city_name'];
            }
        }
        
        return [$countries, $regions, $cities];
    }
}