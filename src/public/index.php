<?php

use Core\App;
use Controller\UserController;
use Controller\ProductController;
use Controller\CartController;
use Controller\OrderController;

require_once './../Core/Autoloader.php';

$path = dirname(__DIR__);

\Core\Autoloader::register($path);

$app = new App();
$app->addRoute('/registration', 'GET', UserController::class, 'getRegistrate');
$app->addRoute('/registration', 'POST', UserController::class, 'registrate');
$app->addRoute('/login', 'GET', UserController::class, 'getLogin');
$app->addRoute('/login', 'POST', UserController::class, 'login');
$app->addRoute('/catalog', 'GET', ProductController::class, 'getProducts');
$app->addRoute('/edit-profile', 'GET', UserController::class, 'getEditProfile');
$app->addRoute('/edit-profile', 'POST', UserController::class, 'editProfile');
$app->addRoute('/profile', 'GET', UserController::class, 'getProfile');
$app->addRoute('/profile', 'POST', UserController::class, 'profile');
$app->addRoute('/cart', 'GET', CartController::class, 'getCart');
$app->addRoute('/cart', 'POST', CartController::class, 'addProduct');
$app->addRoute('/add-product', 'POST', CartController::class, 'addProductToCart');
$app->addRoute('/logout', 'GET', UserController::class, 'logout');
$app->addRoute('/create-order', 'GET', OrderController::class, 'getCheckoutForm');
$app->addRoute('/create-order', 'POST', OrderController::class, 'handleCheckout');
$app->addRoute('/user-orders', 'GET', OrderController::class, 'getAllOrders');

$app->run();