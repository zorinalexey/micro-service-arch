<?php

namespace App\Core\Middlewares\Common;

abstract class AbstractMiddleware implements MiddlewareInterface
{
    protected int $code = 500;
    
    protected string $message = 'Error';
    
    final public function getMessage (): string
    {
        return $this->message;
    }
    
    public function getHttpCode (): int
    {
        return $this->getCode();
    }
    
    final public function getCode (): int
    {
        return $this->code;
    }
}