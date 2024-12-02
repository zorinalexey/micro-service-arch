<?php

namespace App\Services\Api\V1\CRM;

use App\Core\Request\Request;
use App\Core\Service\AbstractService;

final class AuthService extends AbstractService
{
    public function login (string $login, string $password): array|false
    {
        pdd($login, $password, Request::getInstance());
        return false;
    }
    
    public function logout (Request $request): bool
    {
        
        return false;
    }
}