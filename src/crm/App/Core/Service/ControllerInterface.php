<?php

namespace App\Core\Service;

use App\Core\Request\Request;

interface ControllerInterface
{
    public function create (Request $request): array;
    
    public function update (string|int $id, Request $request): array;
    
    public function view (string|int $id): array;
    
    public function list (Request $request): array;
    
    public function restore (string|int $id): array;
    
    public function groupRestore (Request $request): array;
    
    public function softGroupDelete (Request $request): array;
    
    public function hardGroupDelete (Request $request): array;
    
    public function softDelete (string|int $id): array;
    
    public function hardDelete (string|int $id): bool;
    
    public function createInfo (Request|null $request = null): array;
    
    public function updateInfo (Request|null $request = null): array;
    
    public function actionInfo (Request|null $request = null): array;
}