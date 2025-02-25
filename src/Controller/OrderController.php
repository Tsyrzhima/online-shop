<?php

namespace Controller;

use Model\OrderProduct;
use Model\Cart;
use Model\Product;
use Model\Order;


class OrderController
{
    private Cart $cartModel;
    private Product $productModel;
    private OrderProduct $orderProductModel;
    public function __construct()
    {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
        $this->orderProductModel = new OrderProduct();
    }

    public function getCheckoutForm()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            $orderProducts = $this->cartModel->getAllProductsById($userId);
            if(empty($orderProducts))
            {
                header('Location: /catalog');
                exit();
            }
            $newOrderProducts = $this->newOrderProducts($orderProducts);
            $total = $this->totalOrderProducts($newOrderProducts);
            require_once '../Views/order_form.php';
        } else {
            header('Location: /login');
            exit();
        }
    }
    public function getAllOrders()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }
        $userId = $_SESSION['userId'];

        $orderModel = new Order();
        $userOrders = $orderModel->getAllById($userId);

        $newUserOrders = [];

        foreach ($userOrders as $userOrder) {
            $userOrder['total'] = 0;
            $orderProducts = $this->orderProductModel->getAllProductsByOrderId($userOrder['id']);
            $newOrderProducts = $this->newOrderProducts($orderProducts);
            foreach ($newOrderProducts as $newOrderProduct)
            {
                $newOrderProductsById[$userOrder['id']][] = $newOrderProduct;
                $userOrder['total'] += $newOrderProduct['totalProduct'];
            }
            $newUserOrders[] = $userOrder;
        }
        require_once '../Views/user_orders.php';
    }
    public function handleCheckout()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }

        $data = $_POST;
        $errors = $this->validate($data);
        $userId = $_SESSION['userId'];
        $orderProducts = $this->cartModel->getAllProductsById($userId);
        $newOrderProducts = $this->newOrderProducts($orderProducts);
        $total = $this->totalOrderProducts($newOrderProducts);

        if (empty($errors)) {
            $userId = $_SESSION['userId'];

            $orderModel = new Order();
            $orderId = $orderModel->create($data, $userId);

            foreach ($orderProducts as $orderProduct)
            {
                $this->orderProductModel->create($orderId, $orderProduct['product_id'], $orderProduct['amount']);
            }
            $this->cartModel->deleteByUserId($userId);
            header('Location: /user-orders');
            exit();
        }else{
            require_once '../Views/order_form.php';
        }

    }
    private function validate(array $data): array
    {
        $errors = [];

        if (isset($data['name'])) {
            if (strlen($data['name']) < 2) {
                $errors['name'] = 'Имя пользователя должно быть больше 2 символов';
            } elseif (!preg_match('/^[a-zA-Zа-яА-Я0-9_\-\.]+$/u', $data['name'])) {
                $errors['name'] = "Имя пользователя может содержать только буквы, цифры, символы '_', '-', '.'";
            }
        } else {
            $errors['name'] = 'Введите имя';
        }

        if (isset($data['address'])) {
            if (!preg_match('/^[\d\s\w\.,-]+$/u', $data['address'])) {
                $errors['address'] = "Адрес содержит недопустимые символы";
            }elseif (!preg_match('/[а-яА-ЯёЁ]+\s+\d+/', $data['address'])) {
                $errors['address'] = "Адрес должен содержать номер дома и улицу";
            }
        } else {
            $errors['address'] = 'Введите адрес';
        }

        if (isset($data['phone'])) {
            $cleanedPhone = preg_replace('/\D/', '', $data['phone']);
            if(strlen($cleanedPhone) < 11) {
                $errors['phone'] = 'Номер телефона не может быть меньше 11 символов';
            }elseif (!preg_match('/^\+\d+$/', $data['phone'])) {
                $errors['phone'] = "Номер телефона должен начинаться с '+' и содержать только цифры после него";
            }
        } else {
            $errors['phone'] = 'Введите имя';
        }
        return $errors;
    }
    public function newOrderProducts(array $orderProducts): array
    {
        $newOrderProducts = [];
        foreach ($orderProducts as $orderProduct)
        {
            $product = $this->productModel->getById($orderProduct['product_id']);
            $orderProduct['name'] = $product['name'];
            $orderProduct['price'] = $product['price'];
            $orderProduct['totalProduct'] = $orderProduct['amount'] * $orderProduct['price'];
            $newOrderProducts[] = $orderProduct;
        }
        return $newOrderProducts;
    }
    public function totalOrderProducts(array $newOrderProducts): int
    {
        $total = 0;
        foreach ($newOrderProducts as $newOrderProduct)
        {
            $total += $newOrderProduct['totalProduct'];
        }
        return $total;
    }
}