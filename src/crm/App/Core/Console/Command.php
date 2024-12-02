<?php

namespace App\Core\Console;

abstract class Command extends AbstractCommand
{
    
    final protected function info (string $string)
    {
        echo $string;
    }
}