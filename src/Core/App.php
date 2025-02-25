<?php

namespace Core;

use Controller\UserController;
use Controller\ProductController;
use Controller\CartController;
use Controller\OrderController;

class App
{
    private array $routes = [
        '/registration' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getRegistrate',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'registrate',
            ],
        ],
        '/login' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'login',
            ],
        ],
        '/catalog' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'getProducts',
            ],
        ],
        '/edit-profile' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getEditProfile',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'editProfile',
            ],
        ],
        '/profile' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getProfile',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'profile',
            ],
        ],
        '/cart' => [
            'GET' => [
                'class' => CartController::class,
                'method' => 'getCart',
            ],
        ],
        '/add-product' => [
            'POST' => [
                'class' => CartController::class,
                'method' => 'addProductToCart',
            ],
        ],
        '/logout' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'logout',
                    ]
        ],
        '/create-order' => [
            'GET' => [
                'class' => OrderController::class,
                'method' => 'getCheckoutForm',
            ],
            'POST' => [
                'class' => OrderController::class,
                'method' => 'handleCheckout',
            ]
        ],
        '/user-orders' => [
            'GET' => [
                'class' => OrderController::class,
                'method' => 'getAllOrders',
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