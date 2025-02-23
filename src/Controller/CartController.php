<?php

namespace Controller;

use Model\Cart;
use Model\Product;

class CartController
{
    private Cart $cartModel;
    public function __construct()
    {
        $this->cartModel = new Cart();
    }
    public function getCart()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            $newUserProducts = [];
            $userProducts = $this->cartModel->getAllProductsById($userId);
            foreach ($userProducts as $userProduct)
            {
                $productById = new Product();
                $product = $productById->getById($userProduct['product_id']);
                $userProduct['name'] = $product['name'];
                $userProduct['price'] = $product['price'];
                $userProduct['description'] = $product['description'];
                $userProduct['image_url'] = $product['image_url'];
                $newUserProducts[] = $userProduct;
            }
            require_once '../Views/cart.php';
        } else {
            header('Location: /login');
            exit();
        }
    }

    public function addProductToCart()
    {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (isset($_SESSION['userId'])) {

            $userId = $_SESSION['userId'];
            $data = $_POST;
            $errors = $this->validate($data);
            if (empty($errors)) {
                $product = $this->cartModel->isUserHaveProduct($userId, $data['product_id']);
                if ($product) {
                    $amount = $product['amount'] + $data['amount'];
                    $this->cartModel->incrementProductAmount($userId, $data['product_id'], $amount);
                } else {
                    $this->cartModel->addProduct($userId, $data['product_id'], $data['amount']);
                }
            }
            header('Location: /catalog');
        } else {
            header('Location: /login');
            exit();
        }
    }

    private function validate(array $data): array
    {
        $errors = [];

        if (isset($data['product_id'])) {
            if (!is_numeric($data['product_id'])) {
                $errors['product_id'] = "id продукта может содержать только цифры";
            } else {
                $productModel = new Product();
                $product = $productModel->getById($data['product_id']);
                if (!$product) {
                    $errors['product_id'] = 'id c таким продуктом не существует';
                }
            }
        } else {
            $errors['product_id'] = 'Введите id';
        }

        if (isset($data['amount'])) {
            if (!is_numeric($data['amount'])) {
                $errors['amount'] = "количество продукта может содержать только цифры";
            }
        } else {
            $errors['amount'] = 'Введите количество';
        }
        return $errors;
    }

}