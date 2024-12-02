<?php

use App\Services\Api\V1\CRM\CityService;

require_once __DIR__ . '/../crm/boot.php';

$routes = setRoutes(CityService::class, 'city');

run($routes);
