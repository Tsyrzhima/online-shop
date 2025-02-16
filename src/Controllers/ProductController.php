<?php

class ProductController
{
    public function getProducts()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (isset($_SESSION['userId'])) {
            require_once '../Model/Product.php';
            $productModel = new Product();
            $products = $productModel->getAll();
            require_once '../Views/catalog.php';
        } else {
            header('Location: login');
        }
    }


}