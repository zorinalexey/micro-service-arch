<?php

use App\Services\Api\V1\CRM\MarketplaceService;

require_once __DIR__ . '/../crm/boot.php';

$routes = setRoutes(MarketplaceService::class, 'marketplace');

run($routes);
