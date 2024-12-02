<?php

namespace App\Filters\Api\V1\CRM\Traits;

use App\Core\DTO\FilterDto;
use App\Core\Request\Request;
use App\Services\Api\V1\CRM\DeveloperService;

trait DeveloperFilterTrait
{
    protected static function developerFilterDto (): array
    {
        $request = clone(Request::getInstance());
        $request->per_page = 'all';
        unset($request->path);
        $list = (new DeveloperService())->list($request);
        $options = [];
        
        foreach ($list['data'] as $item) $options[$item['id']] = $item['name'];
        
        return FilterDto::make('developer[]', $options, 'dropdown-checkbox', 'developer.label', 'developer.placeholder');
    }
}