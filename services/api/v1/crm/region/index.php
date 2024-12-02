<?php

use App\Services\Api\V1\CRM\RegionService;

require_once __DIR__ . '/../crm/boot.php';

$routes = setRoutes(RegionService::class, 'region');

run($routes);

