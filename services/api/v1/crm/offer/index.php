<?php

use App\Services\Api\V1\CRM\OfferService;

require_once __DIR__ . '/../crm/boot.php';

$routes = setRoutes(OfferService::class, 'offer');

run($routes);
