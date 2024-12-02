<?php

use App\Middlewares\Api\V1\AuthMiddleware;
use App\Services\Api\V1\CRM\AuthService;

return [
    'POST' => [
        "api/v1/crm/auth/login" => [
            'service' => AuthService::class,
            'method' => 'login',
            'request' => $request,
            'params' => [
                'login' => &$request->login,
                'password' => &$request->password,
            ],
        ],
    ],
    'GET' => [
        "api/v1/crm/auth/login" => [
            'service' => AuthService::class,
            'method' => 'login',
            'request' => $request,
            'params' => [
                'login' => &$request->login,
                'password' => &$request->password,
            ],
        ],
        "api/v1/crm/auth/logout" => [
            'service' => AuthService::class,
            'method' => 'logout',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
            ],
            'params' => [
                'request' => $request,
            ],
        ],
    ],
    'PUT' => [
        "api/v1/crm/auth/logout" => [
            'service' => AuthService::class,
            'method' => 'logout',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
            ],
            'params' => [
                'request' => $request,
            ],
        ],
    ],
    'PATCH' => [
        "api/v1/crm/auth/logout" => [
            'service' => AuthService::class,
            'method' => 'logout',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
            ],
            'params' => [
                'request' => $request,
            ],
        ],
    ],
];