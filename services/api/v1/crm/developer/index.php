<?php

use App\Services\Api\V1\CRM\DeveloperService;

require_once __DIR__ . '/../crm/boot.php';

$routes = setRoutes(DeveloperService::class, 'developer');

run($routes);
