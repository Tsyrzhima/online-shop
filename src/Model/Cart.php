<?php

require_once '../Model/Model.php';
class Cart extends Model
{
    public function getAllProductsById(int $userId): array|false
    {
        $statement = $this->PDO->query("SELECT * FROM user_products WHERE user_id = $userId");
        $userProducts = $statement->fetchAll();
        return $userProducts;
    }
    public function isUserHaveProduct(int $userId, int $productId): array|false
    {
        $statement = $this->PDO->query("SELECT * FROM user_products WHERE product_id = $productId AND user_id = $userId");
        $product = $statement->fetch();
        return $product;
    }
    public function incrementProductAmount(int $userId, int $productId, int $amount)
    {
        $statement = $this->PDO->prepare("UPDATE user_products SET amount = :amount WHERE product_id = $productId AND user_id = $userId");
        $statement->execute(['amount' => $amount]);
    }
    public function addProduct(int $userId, int $productId, int $amount)
    {
        $statement = $this->PDO->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES ($userId, $productId, :amount)");
        $statement->execute(['amount' => $amount]);
    }

}