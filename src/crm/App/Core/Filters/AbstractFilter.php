<?php

namespace App\Core\Filters;

use App\Core\Service\AbstractService;
use App\Core\SqlBuilder\CRUD\Select;
use App\Core\SqlBuilder\CRUD\Update;
use DateTime;

abstract class AbstractFilter
{
    protected Select|Update $builder;
    protected array $filterColumns = [];
    protected readonly AbstractService $service;
    private array $filters;
    
    protected function __construct (Select|Update $builder, AbstractService $service, array $filters = [])
    {
        $this->builder = $builder;
        $this->filters = $filters;
        $this->service = $service;
        
        if (!isset($this->filters['trashed'])) {
            $this->filters['trashed'] = 'default';
        }
        
        if (!isset($this->filters['sort'])) {
            if (in_array('name', [...$this->getColumns(), ...$this->service->getFields()], false)) {
                $this->filters['sort']['name'] = 'asc';
            } else {
                $this->filters['sort']['id'] = 'desc';
            }
        }
    }
    
    final protected function getColumns (): array
    {
        return $this->filterColumns;
    }
    
    final public static function filter (Select|Update $builder, AbstractService $service, array $filters = []): void
    {
        $filter = new static($builder, $service, $filters);
        $filter->setFilterColumns();
        $filter->apply();
    }
    
    private function setFilterColumns (): void
    {
        foreach ([...$this->service->getBuilderFields()] as $field) {
            $this->filterColumns[] = preg_replace('~^([\w.]+)(.+)?$~', '$1', $field);
        }
    }
    
    final protected function apply (): void
    {
        foreach ($this->filters as $method => $value) {
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
    
    abstract public static function getSorts (): array;
    
    abstract public static function getFilters (): array;
    
    final protected function id (string|int|array|null $ids): void
    {
        if (!$ids) {
            return;
        }
        
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        
        $this->builder->where(["{$this->service->getTableName()}.id", $ids], ["{$this->service->getTableName()}.int_id::TEXT", $ids]);
    }
    
    final protected function int_id (string|int|array $ids): void
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        
        $this->builder->where(["{$this->service->getTableName()}.id", $ids]);
    }
    
    final protected function created_at (string|DateTime $date): void
    {
        if (!($date instanceof DateTime)) {
            $date = (new DateTime($date))->format('Y-m-d H:i:s');
        }
        
        $this->builder->where(["{$this->service->getTableName()}.created_at", $date]);
    }
    
    final protected function deleted_at (string|DateTime $date): void
    {
        if (!($date instanceof DateTime)) {
            $date = (new DateTime($date))->format('Y-m-d H:i:s');
        }
        
        $this->builder->where(["{$this->service->getTableName()}.deleted_at", $date]);
    }
    
    final protected function updated_at (string|DateTime $date): void
    {
        if (!($date instanceof DateTime)) {
            $date = (new DateTime($date))->format('Y-m-d H:i:s');
        }
        
        $this->builder->where(["{$this->service->getTableName()}.updated_at", $date]);
    }
    
    final protected function sort (array $sort): void
    {
        if ($this->builder instanceof Select) {
            foreach ($sort as $key => $value) {
                if (($method = 'sort' . ucfirst($key)) && method_exists($this, $method)) {
                    $this->$method($value);
                } elseif (in_array($key, $this->getColumns(), false) && method_exists($this->builder, 'orderBy')) {
                    $this->builder->orderBy($key, $value);
                }
            }
        }
    }
    
    final protected function trashed (string|int|bool $trashed): void
    {
        $trashed = mb_strtolower(trim($trashed));
        
        if ($trashed === 'only') {
            $this->builder->where(["{$this->service->getTableName()}.deleted_at", null, 'IS NOT NULL']);
        }
        
        if ($trashed === 'default') {
            $this->builder->where(["{$this->service->getTableName()}.deleted_at", null, 'IS NULL']);
        }
    }
    
    final protected function search (string $search): void
    {
        $words = explode(' ', $search);
        
        foreach ($words as $key => $word) if (empty($word)) unset($words[$key]);
        
        foreach ($words as $key => $word) {
            $params = [];
            
            foreach ($this->getColumns() as $field) {
                $params[] = ["{$field}::TEXT", "%{$word}%", 'ILIKE'];
            }
            
            if ($key === 0) {
                $this->builder->where(...$params);
            } else {
                $this->builder->or(...$params);
            }
        }
    }
    
    protected function sortId (string $direction): void
    {
        $this->builder->orderBy($this->service->getTableName() . '.int_id', $direction);
    }
}