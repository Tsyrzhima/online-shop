<?php

namespace Model;

class OrderProduct extends Model
{
    private int $id;
    private int $orderId;
    private int $productId;
    private int $amount;
    private Product $product;
    private int $sum;

    public function getTableName(): string
    {
        return 'order_products';
    }

    public function create(int $orderId, int $productId, int $amount)
    {
        $stmt = $this->PDO->prepare
        (
            "INSERT INTO {$this->getTableName()} (order_id, product_id, amount) VALUES (:order_id, :product_id, :amount)"
        );
        $stmt->execute(['order_id' => $orderId, 'product_id' => $productId, 'amount' => $amount]);
    }
    public function getAllByOrderId(int $orderId): array|false
    {
        $stmt = $this->PDO->prepare("SELECT * FROM {$this->getTableName()} WHERE order_id = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        $orderProducts = $stmt->fetchAll();
        $newOrderProducts = [];
        foreach ($orderProducts as $orderProduct)
        {
            $newOrderProducts[] = $this->createObj($orderProduct);
        }

        return $newOrderProducts;
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

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }
    public function getProduct(): Product
    {
        return $this->product;
    }
    public function getSum(): int
    {
        return $this->sum;
    }
    public function setSum(int $sum): void
    {
        $this->sum = $sum;
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