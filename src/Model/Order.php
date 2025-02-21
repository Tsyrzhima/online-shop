<?php

namespace Model;

class Order extends Model
{
    public function add(int $userId, int $orderProductId, string $delivery_address, string $phoneNumber, int $total)
    {
        $statement = $this->PDO->prepare("INSERT INTO orders (user_id, order_products_id, delivery_address, phone_number, total)
                                                    VALUES ($userId, $orderProductId, :address, :phone, $total)");
        $statement->execute(['address' => $delivery_address, 'phone' => $phoneNumber]);
    }
    public function addProduct(int $productId, string $name, int $price, int $amount, int $totalProducts): int
    {
        $statement = $this->PDO->prepare("INSERT INTO order_products (product_id, name, price, amount, total_products)
                                                    VALUES ($productId, $name, $price, :amount, $totalProducts)");
        $statement->execute(['amount' => $amount]);
        return $this->PDO->lastInsertId();
    }
}