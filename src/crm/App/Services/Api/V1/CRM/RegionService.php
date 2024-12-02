<?php

namespace App\Services\Api\V1\CRM;

use App\Core\Request\Request;
use App\Core\Service\AbstractService;
use App\Core\SqlBuilder\CRUD\Select;
use App\Core\SqlBuilder\SqlBuilder;
use App\Filters\Api\V1\CRM\RegionFilter;
use App\Resources\Api\V1\Crm\Region\RegionResource;
use App\Services\Api\V1\CRM\Traits\LocationTrait;

final class RegionService extends AbstractService
{
    use LocationTrait;
    
    protected string $tableName = 'regions';
    protected string $resource = RegionResource::class;
    protected string $filter = RegionFilter::class;
    
    protected array $builderFields = [
        'regions.name AS name',
        'regions.id AS id',
        'regions.int_id AS int_id',
        'co.name AS country_name',
        'co.id AS country_id',
        'co.int_id AS country_int_id',
    ];
    
    protected array $fillable = [
        'name',
        'country_id',
    ];
    
    public function actionInfo (?Request $request = null): array
    {
        [$countries] = $this->getLocations();
        
        return [$countries];
    }
    
    protected function getBuilder (array $data = []): Select
    {
        $countryTable = (new CountryService())->getTableName();
        $builder = SqlBuilder::select($this->getBuilderFields())->table($this->getTableName());
        $builder->join("{$countryTable} AS co", 'co.id', 'regions.country_id')
            ->groupBy(['regions.name', 'regions.id', 'regions.int_id', 'co.name', 'co.id', 'co.int_id']);
        
        $this->filter::filter($builder, $this, $data);
        
        return $builder;
    }
}