<?php

class Product
{
    public function getProducts()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if(isset($_SESSION['userId'])) {
            $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
            $statement = $pdo->query("SELECT * FROM products");
            $products = $statement->fetchAll();
            require_once './pages/catalog_page.php';
        }
        else{
            header('Location: login');
        }
    }

}