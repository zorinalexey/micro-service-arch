<?php

namespace App\Core\Middlewares\Common;

interface MiddlewareInterface
{
    public function handle (): bool;
    
    public function getMessage (): string;
    
    public function getCode (): int;
    
    public function getHttpCode (): int;
}