<?php

namespace Core;

use Request\AddProductToCartRequest;
use Request\AddReviewRequest;
use Request\DecreaceProductFromCartRequest;
use Request\EditProfileRequest;
use Request\HandleCheckoutOrderRequest;
use Request\LoginRequest;
use Request\RegistrateRequest;

class App
{
    private array $routes = [];
    private array $requests = [
        'registrate' => RegistrateRequest::class,
        'login' => LoginRequest::class,
        'editProfile' => EditProfileRequest::class,
        'addProductToCart' => AddProductToCartRequest::class,
        'decreaceProductFromCart' => DecreaceProductFromCartRequest::class,
        'handleCheckout' => HandleCheckoutOrderRequest::class,
        'addReview' => AddReviewRequest::class,
    ];
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
                if($requestMethod === 'POST')
                {
                    //if (isset($requestClasses[$method])) {
                    //    $requestClass = $requestClasses[$method];
                    //    $request = new $requestClass($_POST);
                    //} else {
                    //    $request = null;
                    //}
                    switch ($method) {
                        case 'registrate': $request = new RegistrateRequest($_POST); break;
                        case 'login': $request = new LoginRequest($_POST); break;
                        case 'editProfile': $request = new EditProfileRequest($_POST); break;
                        case 'addProductToCart': $request = new AddProductToCartRequest($_POST); break;
                        case 'decreaceProductFromCart': $request = new DecreaceProductFromCartRequest($_POST); break;
                        case 'handleCheckout': $request = new HandleCheckoutOrderRequest($_POST); break;
                        case 'addReview': $request = new AddReviewRequest($_POST); break;
                        default: $request = null;break;
                    }
                    $controller->$method($request);
                }else{
                    $controller->$method();
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
    public function get(string $route, string $className, string $method)
    {
        $this->routes[$route]['GET'] = [
            'class' => $className,
            'method' => $method,
        ];
    }
    public function post(string $route, string $className, string $method)
    {
        $this->routes[$route]['POST'] = [
            'class' => $className,
            'method' => $method,
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