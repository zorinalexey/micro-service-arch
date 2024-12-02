<?php

namespace App\Core\Response;

use JsonException;
use stdClass;

final class FailResponse extends stdClass
{
    public function __construct (array|null $data = null, string $message = 'Error', int $code = 500, array $errors = [])
    {
        $this->data = $data;
        $this->code = $code;
        $this->success = false;
        $this->error = true;
        $this->errors = $errors;
        $this->message = $message;
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
        ];
        
        return json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }
}