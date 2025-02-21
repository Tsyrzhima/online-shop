<?php

namespace Controller;

use Model\User;
use Model\Cart;
use Model\Product;
use Model\Order;

class OrderController
{
    public function getOrder()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            $userModel = new User();
            $user = $userModel->getById($userId);
            $cartModel = new Cart();
            $newUserProducts = [];
            $total = 0;
            $userProducts = $cartModel->getAllProductsById($userId);
            foreach ($userProducts as $userProduct)
            {
                $productById = new Product();
                $product = $productById->getById($userProduct['product_id']);
                $userProduct['name'] = $product['name'];
                $userProduct['price'] = $product['price'];
                $userProduct['description'] = $product['description'];
                $userProduct['image_url'] = $product['image_url'];
                $total += $userProduct['price'] * $userProduct['amount'];
                $newUserProducts[] = $userProduct;
            }
            require_once '../Views/order_form.php';
        } else {
            header('Location: /login');
            exit();
        }
    }
    public function addOrder()
    {
        $data = $_POST;
        //$errors = $this->validateAdd($data);

        if (empty($errors)) {




            $orderModel= new Order();
            $orderProduct = $orderModel->addProduct();

            $userModel = new User();
            $user = $userModel->getByEmail($data['email']);

        }
        require_once '../Views/order_form.php';
    }
    public function validateAdd($data)
    {

    }
}