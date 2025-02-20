<?php

namespace Model;

require_once '../Model/Model.php';
class Product extends Model
{
    public function getAll(): array|false
    {
        $statement = $this->PDO->query("SELECT * FROM products");
        $products = $statement->fetchAll();
        return $products;
    }
    public function getById(int $productId): array|false
    {
        $statement = $this->PDO->query("SELECT * FROM products WHERE id = $productId");
        $product = $statement->fetch();
        return $product;
    }
}