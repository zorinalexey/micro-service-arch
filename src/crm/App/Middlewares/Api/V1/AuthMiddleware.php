<?php

namespace App\Middlewares\Api\V1;

use App\Core\Middlewares\Common\AbstractMiddleware;

final class AuthMiddleware extends AbstractMiddleware
{
    protected int $code = 401;
    
    protected string $message = 'User Unauthorized';
    
    public function handle (): bool
    {
        
        return true;
    }
}