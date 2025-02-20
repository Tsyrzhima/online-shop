<?php

class App
{
    private array $routes = [
        '/registration' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getRegistrate',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'registrate',
            ],
        ],
        '/login' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'login',
            ],
        ],
        '/catalog' => [
            'GET' => [
                'class' => 'ProductController',
                'method' => 'getProducts',
            ],
        ],
        '/edit-profile' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getEditProfile',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'editProfile',
            ],
        ],
        '/profile' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getProfile',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'profile',
            ],
        ],
        '/cart' => [
            'GET' => [
                'class' => 'CartController',
                'method' => 'getCart',
            ],
        ],
        '/add-product' => [
            'POST' => [
                'class' => 'CartController',
                'method' => 'addProductToCart',
            ],
        ],
        '/logout' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'logout',
                    ]
        ]
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
                $controller->$method();
            } else {
                echo "$requestMethod для адреса $requestUri не поддерживается";
            }
        } else {
            http_response_code(404);
            require_once '../404.php';
        }
    }

}