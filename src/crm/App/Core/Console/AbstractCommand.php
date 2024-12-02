<?php

namespace App\Core\Console;


abstract class AbstractCommand implements CommandInterface
{
    protected array $args = [];
    protected array $commands = [];
    protected string $name = '';
    
    final public function __construct (string ...$args)
    {
        $this->args = $args;
    }
    
    final public function run (): bool
    {
        return $this->handle();
    }
    
    abstract protected function handle (): bool;
}