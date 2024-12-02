<?php

namespace App\Core\Service;

interface ServiceInterface
{
    public function getErrors (): array;
    
    public function getMessage (): string|null;
    
    public function getTableName (): string;
    
    public function getFields (): array;
    
    public function getBuilderFields (): array;
}