<?php

namespace Model;

class OrderProduct extends Model
{
    private int $id;
    private int $orderId;
    private int $productId;
    private int $amount;
    private Product $product;
    private int $total;

    public function create(int $orderId, int $productId, int $amount)
    {
        $stmt = $this->PDO->prepare
        (
            "INSERT INTO order_products(order_id, product_id, amount) VALUES (:order_id, :product_id, :amount)"
        );
        $stmt->execute(['order_id' => $orderId, 'product_id' => $productId, 'amount' => $amount]);
    }
    public function getAllByOrderId(int $orderId): array|false
    {
        $stmt = $this->PDO->prepare("SELECT * FROM order_products WHERE order_id = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        $orderProducts = $stmt->fetchAll();
        $newOrderProducts = [];
        foreach ($orderProducts as $orderProduct)
        {
            $newOrderProducts[] = $this->createObj($orderProduct);
        }

        return $newOrderProducts;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    private function createObj(array $orderProduct): self|null
    {
        if(!$orderProduct){
            return null;
        }

        $obj = new self();
        $obj->id = $orderProduct['id'];
        $obj->orderId = $orderProduct['order_id'];
        $obj->productId = $orderProduct['product_id'];
        $obj->amount = $orderProduct['amount'];

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }


}