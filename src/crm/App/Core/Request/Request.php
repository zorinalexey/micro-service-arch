<?php

namespace App\Core\Request;

use stdClass;

class Request extends stdClass
{
    private static self|null $instance = null;
    public string|int|null $id = null;
    public int $page = 1;
    public int|string $per_page = 50;
    private stdClass $headers;
    
    private function __construct (array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
        
        if ($this->page < 1) {
            $this->page = 1;
        }
        
        $this->headers = new stdClass();
        
        foreach (getallheaders() as $key => $value) {
            $this->headers->{$key} = $value;
        }
        
        self::$instance = $this;
    }
    
    public static function getInstance (array $data = []): self
    {
        if (self::$instance === null) {
            new self($data);
        }
        
        return self::$instance;
    }
    
    public function get (string $key, $default = null): mixed
    {
        return $this->{$key} ?? $default;
    }
    
    public function validated (): array
    {
        return (array) $this;
    }
    
    public function has (string $key): bool
    {
        return isset($this->{$key});
    }
    
    public function set (string $key, $value): void
    {
        $this->{$key} = $value;
    }
}