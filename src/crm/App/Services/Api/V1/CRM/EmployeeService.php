<?php

namespace App\Services\Api\V1\CRM;

use App\Core\Helpers\Hash;
use App\Core\Request\Request;
use App\Core\Service\AbstractService;
use App\Core\SqlBuilder\CRUD\Select;
use App\Core\SqlBuilder\SqlBuilder;
use App\Filters\Api\V1\CRM\EmployeeFilter;
use App\Resources\Api\V1\Crm\Employee\EmployeeResource;
use App\Services\Api\V1\CRM\Traits\LocationTrait;
use App\Services\Api\V1\CRM\Traits\RoleTrait;

final class EmployeeService extends AbstractService
{
    use LocationTrait, RoleTrait;
    
    protected string $tableName = 'employees';
    protected string $resource = EmployeeResource::class;
    protected string $filter = EmployeeFilter::class;
    
    protected array $builderFields = [
        'employees.id AS id',
        'employees.int_id AS int_id',
        'employees.login AS login',
        'employees.last_name AS last_name',
        'employees.name AS name',
        "CONCAT(employees.name, ' ', employees.last_name) AS full_name",
        'employees.middle_name AS middle_name',
        'employees.date_of_birth AS date_of_birth',
        'employees.virtual_number AS virtual_number',
        'employees.phone AS phone',
        'employees.email AS email',
        'employees.is_blocked AS is_blocked',
        'c.name AS city_name',
        'c.id AS city_id',
        'c.int_id AS city_int_id',
        'r.name AS region_name',
        'r.id AS region_id',
        'r.int_id AS region_int_id',
        'co.name AS country_name',
        'co.id AS country_id',
        'co.int_id AS country_int_id',
        'ro.id AS role_id',
        'ro.int_id AS role_int_id',
        'ro.name AS role_name',
        'ro.default_page AS role_default_page',
        'ro.permissions AS permissions',
        'ro.deleted AS role_deleted',
        'ro.updated AS role_updated',
    ];
    
    protected array $fillable = [
        'login',
        'last_name',
        'name',
        'middle_name',
        'date_of_birth',
        'virtual_number',
        'phone',
        'operator_group_id',
        'external_id',
        'add_external_number',
        'role_id',
        'city_id',
        'region_id',
        'country_id',
        'is_blocked',
        'status',
        'email',
        'password',
    ];
    
    public function actionInfo (Request|null $request = null): array
    {
        $roles = $this->getRoles();
        [$countries, $regions, $cities] = $this->getLocations();
        
        return [
            'data' => compact('roles', 'countries', 'regions', 'cities')
        ];
    }
    
    protected function getBuilder (array $data = []): Select
    {
        $countryTable = (new CountryService())->getTableName();
        $regionTable = (new RegionService())->getTableName();
        $cityTable = (new CityService())->getTableName();
        $roleTable = (new RoleService())->getTableName();
        $builder = SqlBuilder::select($this->getBuilderFields())->table($this->getTableName())
            ->join("{$cityTable} AS c", 'c.id', 'employees.city_id')
            ->join("{$regionTable} AS r", 'r.id', 'employees.region_id')
            ->join("{$countryTable} AS co", 'co.id', 'employees.country_id')
            ->join("{$roleTable} AS ro", 'ro.id', 'employees.role_id')
            ->groupBy([
                'employees.id', 'employees.int_id', 'employees.login', 'employees.last_name', 'employees.name',
                'employees.middle_name', 'employees.date_of_birth', 'employees.virtual_number', 'employees.phone',
                'employees.email', 'employees.is_blocked', 'c.name', 'c.id', 'c.int_id', 'r.name', 'r.id', 'r.int_id',
                'co.name', 'co.id', 'co.int_id', 'ro.id', 'ro.int_id', 'ro.name', 'ro.default_page',
                'ro.deleted', 'ro.updated'
            ]);
        
        $this->filter::filter($builder, $this, $data);
        
        return $builder;
    }
    
    protected function setAddParams (array &$params, Request|array|null $request = null): void
    {
        if ($request && ($request->get('city_id')) && $city = (new CityService())->view($request->city_id)['data'] ?? null) {
            $params['city_id'] = $request->city_id;
            $params['region_id'] = $city['region']['id'];
            $params['country_id'] = $city['country']['id'];
        }
        
        if ($request && ($request->get('password'))) {
            $params['password'] = Hash::make($request->password);
        }
    }
}