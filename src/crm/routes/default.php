<?php

use App\Middlewares\Api\V1\AuthMiddleware;
use App\Middlewares\Api\V1\PermissionsMiddleware;

return [
    'GET' => [
        "api/v1/crm/{$entity}" => [
            'service' => $service,
            'method' => 'list',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
                PermissionsMiddleware::class,
            ],
            'params' => [
                'request' => $request,
            ],
        ],
        "api/v1/crm/{$entity}/(?<id>[\w-]+)" => [
            'service' => $service,
            'method' => 'view',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
                PermissionsMiddleware::class,
            ],
            'params' => [
                'id' => &$request->id,
            ]
        ],
        "api/v1/crm/{$entity}/get/action-info" => [
            'service' => $service,
            'method' => 'actionInfo',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
            ],
            'params' => [
                'request' => $request,
            ],
        ],
        "api/v1/crm/{$entity}/get/update-info" => [
            'service' => $service,
            'method' => 'updateInfo',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
            ],
            'params' => [
                'request' => $request,
            ],
        ],
        "api/v1/crm/{$entity}/get/create-info" => [
            'service' => $service,
            'method' => 'createInfo',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
            ],
            'params' => [
                'request' => $request,
            ],
        ],
    ],
    'POST' => [
        "api/v1/crm/{$entity}" => [
            'service' => $service,
            'method' => 'create',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
                PermissionsMiddleware::class,
            ],
            'params' => [
                'request' => $request,
            ]
        ],
    ],
    'DELETE' => [
        "api/v1/crm/{$entity}/soft/(?<id>[\w-]+)" => [
            'service' => $service,
            'method' => 'softDelete',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
                PermissionsMiddleware::class,
            ],
            'params' => [
                'id' => &$request->id,
            ]
        ],
        "api/v1/crm/{$entity}/hard/(?<id>[\w-]+)" => [
            'service' => $service,
            'method' => 'hardDelete',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
                PermissionsMiddleware::class,
            ],
            'params' => [
                'id' => &$request->id,
            ]
        ],
        "api/v1/crm/{$entity}/group/hard" => [
            'service' => $service,
            'method' => 'hardGroupDelete',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
                PermissionsMiddleware::class,
            ],
            'params' => [
                'request' => $request,
            ]
        ],
        "api/v1/crm/{$entity}/group/soft" => [
            'service' => $service,
            'method' => 'softGroupDelete',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
                PermissionsMiddleware::class,
            ],
            'params' => [
                'request' => $request,
            ]
        ],
    ],
    'PUT' => [
        "api/v1/crm/{$entity}/(?<id>[\w-]+)" => [
            'service' => $service,
            'method' => 'restore',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
                PermissionsMiddleware::class,
            ],
            'params' => [
                'id' => &$request->id,
            ]
        ],
        "api/v1/crm/{$entity}/group" => [
            'service' => $service,
            'method' => 'groupRestore',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
                PermissionsMiddleware::class,
            ],
            'params' => [
                'request' => $request,
            ]
        ],
    ],
    'PATCH' => [
        "api/v1/crm/{$entity}/(?<id>[\w-]+)" => [
            'service' => $service,
            'method' => 'update',
            'request' => $request,
            'middlewares' => [
                AuthMiddleware::class,
                PermissionsMiddleware::class,
            ],
            'params' => [
                'id' => &$request->id,
                'request' => $request,
            ]
        ],
    ],
];