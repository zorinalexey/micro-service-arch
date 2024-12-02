<?php

use App\Services\Api\V1\CRM\CountryService;

require_once __DIR__ . '/../crm/boot.php';

$routes = setRoutes(CountryService::class, 'country');

run($routes);
