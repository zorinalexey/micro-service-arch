<?php

namespace App\Core\Resources\Common;

use stdClass;

abstract class AbstractResource extends stdClass
{
    protected array $data = [];
    
    public function __construct (array $data)
    {
        $this->data = $data;
    }
    
    public static function collection (array $data): array
    {
        $params = [];
        
        foreach ($data as $item) $params[] = self::make($item);
        
        return $params;
    }
    
    public static function make (array $data): array
    {
        return (new static($data))->toArray();
    }
    
    protected function toArray (): array
    {
        return $this->data;
    }
    
    public function __get ($name): mixed
    {
        return $this->data[$name] ?? null;
    }
    
    protected function links (string $link, string|int|null $id): array
    {
        if ($id) {
            return [
                'view' => $this->getLink($link, $id, 'GET', '', 'view'),
                'restore' => $this->getLink($link, $id, 'PUT', '', 'restore'),
                'update' => $this->getLink($link, $id, 'PATCH', '', 'update'),
                'delete_soft' => $this->getLink($link, $id, 'DELETE', '/soft', 'delete_soft'),
                'delete_hard' => $this->getLink($link, $id, 'DELETE', '/hard', 'delete_hard'),
            ];
        }
        
        return [];
    }
    
    private function getLink (string $path, string|int $id, string $request_method, string $addPath = '', string|null $permissionPath = null): array
    {
        $patch = $path . $addPath;
        $link = $_SERVER['HTTP_HOST'] . '/' . trim($patch, '/');
        
        return [
            'url' => $link . '/' . $id,
            'title' => str_replace('/', '_', $patch . '/' . $permissionPath),
            'method' => $request_method,
            'permission_key' => str_replace('/', '.', $patch . '/' . $permissionPath),
        ];
    }
}