<?php

namespace Controller;

class ProductController
{
    public function getProducts()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (isset($_SESSION['userId'])) {
            $productModel = new \Model\Product();
            $products = $productModel->getAll();
            require_once '../Views/catalog.php';
        } else {
            header('Location: login');
        }
    }


}