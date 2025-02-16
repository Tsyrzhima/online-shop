<?php
class Product
{
    public function getAll(): array|false
    {
        $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
        $statement = $pdo->query("SELECT * FROM products");
        $products = $statement->fetchAll();
        return $products;
    }
    public function getById(int $productId): array|false
    {
        $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
        $statement = $pdo->query("SELECT * FROM products WHERE id = $productId");
        $product = $statement->fetch();
        return $product;
    }
}