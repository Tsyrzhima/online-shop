<?php

namespace Core;

use Request\AddProductToCartRequest;
use Request\AddReviewRequest;
use Request\DecreaceProductFromCartRequest;
use Request\EditProfileRequest;
use Request\HandleCheckoutOrderRequest;
use Request\LoginRequest;
use Request\RegistrateRequest;
use Service\Logger\LoggerInterface;
use Service\Logger\LoggerFileService;
use Service\Logger\LoggerDbService;

class App
{
    private array $routes = [];
    protected LoggerInterface $loggerService;
    public function __construct()
    {
        //$this->loggerService = new LoggerFileService();
        $this->loggerService = new LoggerDbService();
    }
    public function run()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if (array_key_exists($requestUri, $this->routes)) {
            $routeMethods = $this->routes[$requestUri];
            if (array_key_exists($requestMethod, $routeMethods)) {
                $handler = $routeMethods[$requestMethod];
                $class = $handler['class'];
                $method = $handler['method'];
                $controller = new $class();
                $requestClass = $handler['request'];
                try{
                    if($requestClass !== null){
                        $request = new $requestClass($_POST);
                        $controller->$method($request);
                    }else{
                        $controller->$method();
                    }
                } catch (\Throwable $exception) {
                    $this->loggerService->error($exception);
                    require_once '../500.php';
                }

            } else {
                echo "$requestMethod для адреса $requestUri не поддерживается";
            }
        } else {
            http_response_code(404);
            require_once '../404.php';
        }
    }
    public function addRoute(string $route, string $routeMethod, string $className, string $method)
    {
        $this->routes[$route][$routeMethod] = [
            'class' => $className,
            'method' => $method,
        ];
    }
    public function get(string $route, string $className, string $method, string $requestClass = null)
    {
        $this->routes[$route]['GET'] = [
            'class' => $className,
            'method' => $method,
            'request' => $requestClass,
        ];
    }
    public function post(string $route, string $className, string $method, string $requestClass = null)
    {
        $this->routes[$route]['POST'] = [
            'class' => $className,
            'method' => $method,
            'request' => $requestClass,
        ];
    }
    public function put(string $route, string $className, string $method)
    {
        $this->routes[$route]['PUT'] = [
            'class' => $className,
            'method' => $method,
        ];
    }
    public function delete(string $route, string $className, string $method)
    {
        $this->routes[$route]['DELETE'] = [
            'class' => $className,
            'method' => $method,
        ];
    }
}