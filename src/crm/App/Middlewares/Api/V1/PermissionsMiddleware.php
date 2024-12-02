<?php

namespace App\Middlewares\Api\V1;

use App\Core\Middlewares\Common\AbstractMiddleware;

final class PermissionsMiddleware extends AbstractMiddleware
{
    protected int $code = 403;
    
    protected string $message = 'You do not have permission to access this resource';
    
    public function handle (): bool
    {
        
        return true;
    }
}