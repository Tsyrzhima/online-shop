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

    public static function getTableName(): string
    {
        return 'order_products';
    }

    public static function create(int $orderId, int $productId, int $amount)
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare
        (
            "INSERT INTO $tableName (order_id, product_id, amount) VALUES (:order_id, :product_id, :amount)"
        );
        $stmt->execute(['order_id' => $orderId, 'product_id' => $productId, 'amount' => $amount]);
    }
    public static function getAllByOrderId(int $orderId): array|false
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare("SELECT * FROM $tableName WHERE order_id = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        $orderProducts = $stmt->fetchAll();
        $newOrderProducts = [];
        foreach ($orderProducts as $orderProduct)
        {
            $newOrderProducts[] = static::createObj($orderProduct, $orderProduct['id']);
        }

        return $newOrderProducts;
    }
    public static function createObj(array $orderProduct, int $id): self|null
    {
        if(!$orderProduct){
            return null;
        }

        $obj = new self();
        $obj->id = $id;
        $obj->orderId = $orderProduct['order_id'];
        $obj->productId = $orderProduct['product_id'];
        $obj->amount = $orderProduct['amount'];

        return $obj;
    }
    public static function createObjWithProduct(array $orderProduct, int $id): self|null
    {
        if(!$orderProduct){
            return null;
        }

        $obj = new self();
        $obj->id = $id;
        $obj->orderId = $orderProduct['order_id'];
        $obj->productId = $orderProduct['product_id'];
        $obj->amount = $orderProduct['amount'];

        $product = Product::createObj($orderProduct, $orderProduct['product_id']);
        $obj->setProduct($product);
        $obj->sum = $orderProduct['amount'] * $product->getPrice();

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