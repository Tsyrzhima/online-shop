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
$app->post('/registration', UserController::class, 'registrate', \Request\RegistrateRequest::class);
$app->get('/login', UserController::class, 'getLogin');
$app->post('/login', UserController::class, 'login', \Request\LoginRequest::class);
$app->get('/catalog', ProductController::class, 'getProducts');
$app->get('/edit-profile', UserController::class, 'getEditProfile');
$app->post('/edit-profile', UserController::class, 'editProfile', \Request\EditProfileRequest::class);
$app->get('/profile', UserController::class, 'getProfile');
$app->post('/profile', UserController::class, 'profile');
$app->get('/cart', CartController::class, 'getCart');
$app->post('/add-product', CartController::class, 'addProductToCart', \Request\AddProductToCartRequest::class);
$app->post('/decreace-product', CartController::class, 'decreaceProductFromCart', \Request\DecreaceProductFromCartRequest::class);
$app->get('/logout', UserController::class, 'logout');
$app->get('/create-order', OrderController::class, 'getCheckoutForm');
$app->post('/create-order', OrderController::class, 'handleCheckout', \Request\HandleCheckoutOrderRequest::class);
$app->get('/user-orders', OrderController::class, 'getAllOrders');
$app->post('/product', ProductController::class, 'getProduct');
$app->post('/add-review', ProductController::class, 'addReview', \Request\AddReviewRequest::class);
$app->run();