<?php

namespace App\Core\Console;

interface CommandInterface
{
    public function run (): bool;
}