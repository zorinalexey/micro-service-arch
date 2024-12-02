<?php

namespace App\Services\Api\V1\CRM;

use App\Core\Request\Request;
use App\Core\Service\AbstractService;
use App\Core\SqlBuilder\CRUD\Select;
use App\Core\SqlBuilder\SqlBuilder;
use App\Filters\Api\V1\CRM\CityFilter;
use App\Resources\Api\V1\Crm\City\CityResource;
use App\Services\Api\V1\CRM\Traits\LocationTrait;

final class CityService extends AbstractService
{
    use LocationTrait;
    
    protected string $tableName = 'cities';
    protected string $resource = CityResource::class;
    protected string $filter = CityFilter::class;
    
    protected array $builderFields = [
        'cities.id AS id',
        'cities.int_id AS int_id',
        'cities.name AS name',
        'cities.latitude AS latitude',
        'cities.longitude AS longitude',
        'r.name AS region_name',
        'r.id AS region_id',
        'r.int_id AS region_int_id',
        'co.name AS country_name',
        'co.id AS country_id',
        'co.int_id AS country_int_id',
    ];
    
    protected array $fillable = [
        'name',
        'longitude',
        'latitude',
        'region_id',
        'country_id',
    ];
    
    public function actionInfo (?Request $request = null): array
    {
        [$countries, $regions] = $this->getLocations();
        
        return [$countries, $regions];
    }
    
    protected function getBuilder (array $data = []): Select
    {
        $countryTable = (new CountryService())->getTableName();
        $regionTable = (new RegionService())->getTableName();
        $builder = SqlBuilder::select($this->getBuilderFields())->table($this->getTableName());
        $builder->join("{$regionTable} AS r", 'r.id', 'cities.region_id')
            ->join("{$countryTable} AS co", 'co.id', 'cities.country_id')
            ->groupBy([
                'cities.name', 'cities.int_id', 'cities.id', 'cities.latitude', 'cities.longitude',
                'r.name', 'r.id', 'r.int_id', 'co.name', 'co.id', 'co.int_id'
            ]);
        
        $this->filter::filter($builder, $this, $data);
        
        return $builder;
    }
}