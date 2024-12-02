<?php

namespace App\Core\Console;

use App\Core\Helpers\Arr;

final class Console
{
    private array $commands = [];
    private array $args = [];
    
    public function __construct (...$args)
    {
        $this->commands = config('commands');
        $this->args = $args;
        $this->run();
    }
    
    public function run (): void
    {
        $className = Arr::pull($this->args, 0);
        
        if (isset($this->commands[$className]['class']) && $class = $this->commands[$className]['class']) {
            (new $class(...$this->args))->run();
        }
    }
}