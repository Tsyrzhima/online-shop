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
$app->get('/registration', UserController::class, 'getRegistrate');
$app->post('/registration', UserController::class, 'registrate');
$app->get('/login', UserController::class, 'getLogin');
$app->post('/login', UserController::class, 'login');
$app->get('/catalog', ProductController::class, 'getProducts');
$app->get('/edit-profile', UserController::class, 'getEditProfile');
$app->post('/edit-profile', UserController::class, 'editProfile');
$app->get('/profile', UserController::class, 'getProfile');
$app->post('/profile', UserController::class, 'profile');
$app->get('/cart', CartController::class, 'getCart');
$app->post('/cart', CartController::class, 'addProduct');
$app->post('/add-product', CartController::class, 'addProductToCart');
$app->get('/logout', UserController::class, 'logout');
$app->get('/create-order', OrderController::class, 'getCheckoutForm');
$app->post('/create-order', OrderController::class, 'handleCheckout');
$app->get('/user-orders', OrderController::class, 'getAllOrders');

$app->run();