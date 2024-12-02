<?php

namespace App\Services\Api\V1\CRM;

use App\Core\Service\AbstractService;
use App\Core\SqlBuilder\CRUD\Select;
use App\Core\SqlBuilder\SqlBuilder;
use App\Filters\Api\V1\CRM\BankFilter;
use App\Resources\Api\V1\Crm\Bank\BankResource;

final class BlackListService extends AbstractService
{
    protected string $tableName = 'black_lists';
    protected string $resource = BankResource::class;
    protected string $filter = BankFilter::class;
    
    protected array $builderFields = [
        'black_lists.id AS id',
        'black_lists.int_id AS int_id',
        'black_lists.phone AS phone',
        'black_lists.external_id AS external_id',
        'black_lists.comment AS comment',
        'e.id AS employee_id',
        'e.int_id AS employee_int_id',
        'e.last_name AS last_name',
        'e.name AS name',
        'e.middle_name AS middle_name',
        'CONCAT(e.name, e.last_name) AS full_name',
    ];
    
    protected array $fillable = [
        'phone',
        'employee_id',
        'external_id',
        'comment'
    ];
    
    protected function getBuilder (array $data = []): Select
    {
        $employeeTable = (new EmployeeService())->getTableName();
        $builder = SqlBuilder::select($this->getBuilderFields())->table($this->getTableName());
        $builder->join("{$employeeTable} AS e", 'e.id', 'black_lists.employee_id')
            ->groupBy([
                'black_lists.id', 'black_lists.int_id', 'black_lists.phone', 'black_lists.employee_id', 'black_lists.external_id',
                'black_lists.comment', 'e.name', 'e.int_id', 'e.last_name', 'e.middle_name', 'e.id'
            ]);
        
        $this->filter::filter($builder, $this, $data);
        
        return $builder;
    }
}