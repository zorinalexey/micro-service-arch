<?php

use App\Core\Cache\Cache;
use App\Core\DataBase\DB;
use App\Core\Middlewares\Common\MiddlewareInterface;
use App\Core\Request\Request;
use App\Core\Response\FailResponse;
use App\Core\Response\SuccessResponse;
use App\Core\Service\ServiceInterface;

function pdd (mixed ...$data): void
{
    header('Content-Type: text/html; charset=utf-8');
    dd(...$data);
}

function config (string $path, mixed $default = []): mixed
{
    if (is_file($path)) {
        return require $path;
    }
    
    if (($file = CONFIG_PATH . "/{$path}.php") && is_file($file)) {
        return require $file;
    }
    
    return $default;
}

function cache (string $path = 'cache'): Cache
{
    return new Cache(config($path));
}

function db (string $path = 'db'): DB
{
    return new DB(...config($path));
}

/**
 * @throws JsonException
 */
function getRequest (): Request
{
    $method = $_SERVER['REQUEST_METHOD'];
    $pos = strpos($_SERVER['REQUEST_URI'], '?');
    
    if ($pos !== false) {
        $path = trim(urldecode(substr($_SERVER['REQUEST_URI'], 0, $pos)), '/');
    } else {
        $path = trim(urldecode($_SERVER['REQUEST_URI']), '/');
    }
    
    $jsonData = (file_get_contents('php://input') ?: json_encode([], JSON_THROW_ON_ERROR));
    $json = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);
    
    $callable = match ($method) {
        'POST', 'PUT', 'PATCH', 'DELETE' => static function () use ($json){
            return [...$_REQUEST, ...$json];
        },
        default => static function () use ($json){
            return [...$_GET, ...$json];
        },
    };
    
    return Request::getInstance([...$callable(), 'method' => $method, 'path' => $path]);
}

function trans (string $langFile, string $message, array $params = []): string
{
    $config = config('app');
    
    if (($file = APP_PATH . "/resources/lang/{$config['lang']}/{$langFile}.php") && is_file($file)) {
        $messages = require $file;
        $keys = [];
        $values = [];
        
        if ($params) {
            $keys = array_keys($params);
            $values = array_values($params);
        }
        
        $message = str_replace($keys, $values, $messages[$message] ?? $message);
    }
    
    return $message;
}

function setRoutes (string|null $service = null, string|null $entity = null, Request|null &$request = null): array
{
    if (!$request) {
        $request = getRequest();
    }
    
    $file = APP_PATH . "/routes/{$service}/{$entity}.php";
    
    if (!is_file($file)) {
        $file = APP_PATH . "/routes/{$service}.php";
    }
    
    if (!is_file($file)) {
        $file = APP_PATH . "/routes/default.php";
    }
    
    return require $file;
}

function run (array $routes): void
{
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $actions = $routes[$httpMethod] ?: [];
    $pos = strpos($_SERVER['REQUEST_URI'], '?');
    
    if ($pos !== false) {
        $url = trim(urldecode(substr($_SERVER['REQUEST_URI'], 0, $pos)), '/');
    } else {
        $url = trim(urldecode($_SERVER['REQUEST_URI']), '/');
    }
    
    $response = static function (array $response, ServiceInterface $service): void{
        
        $code = $service->httpCode;
        header('HTTP/1.1 ' . $code);
        $errors = $service->getErrors();
        $error = false;
        
        if (!empty($errors)) {
            $error = true;
        }
        
        echo new SuccessResponse(
            response : $response,
            message : trans(langFile : 'messages', message : $service->getMessage() ?: 'Ok'),
            code : $code,
            errors : $errors,
            error : $error,
        );
    };
    
    try {
        foreach ($actions as $route => $action) {
            if (preg_match("~^{$route}$~ui", $url, $matches)) {
                header('Content-Type: application/json');
                
                $method = $action['method'];
                $params = $action['params'];
                $request = $action['request'];
                
                foreach ($action['middlewares'] ?? [] as $middleware) {
                    if (in_array(MiddlewareInterface::class, class_implements($middleware), false) && !($middleware = new $middleware())->handle()) {
                        header('HTTP/1.1 ' . $middleware->getHttpCode());
                        
                        echo new FailResponse(
                            data : [],
                            message : trans(langFile : 'middleware', message : $middleware->getMessage()),
                            code : $middleware->getCode(),
                        );
                        
                        return;
                    }
                }
                
                $service = new $action['service']();
                
                if (isset($matches['id'])) {
                    $request->set('id', $matches['id']);
                }
                
                $response($service->$method(...$params), $service);
                
                return;
            }
        }
        
        header('HTTP/1.1 404 Not Found');
        
        echo new FailResponse(
            data : [],
            message : trans(langFile : 'middleware', message : 'Page not found'),
            code : 404,
        );
    } catch (Throwable $e) {
        header('HTTP/1.1 500');
        
        echo new FailResponse(
            data : [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ],
            message : trans(langFile : 'errors', message : $e->getMessage()),
            code : (int) $e->getCode(),
            errors : $e->getTrace(),
        );
    }
}
