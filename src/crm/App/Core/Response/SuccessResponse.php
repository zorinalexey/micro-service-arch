<?php

namespace App\Core\Response;

use App\Core\Helpers\Arr;
use JsonException;
use stdClass;

final class SuccessResponse extends stdClass
{
    private bool $error;
    private bool $success;
    private array $errors;
    private string $message;
    private int $code;
    private array|bool $data;
    private int $entity_count = 0;
    private array $response = [];
    
    public function __construct (array|null $response, string $message = 'Ok', int $code = 200, array $errors = [], bool|null $error = false)
    {
        $data = Arr::pull($response, 'data', []);
        
        $this->data = $data;
        $this->code = $code;
        $this->entity_count = is_array($data) ? count($data) : 0;
        $this->success = true;
        $this->error = (bool) $error;
        $this->errors = $errors;
        $this->message = $message;
        $this->response = $response ?: [];
    }
    
    /**
     * @throws JsonException
     */
    public function __toString (): string
    {
        $data = [
            'success' => $this->success,
            'error' => $this->error,
            'data' => $this->data,
            'errors' => $this->errors,
            'message' => $this->message,
            'code' => $this->code,
            'entity_count' => $this->entity_count,
            ...$this->response,
        ];
        
        ksort($data);
        
        return json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }
}