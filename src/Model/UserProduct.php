<?php

namespace Model;

class UserProduct extends Model
{
    private int $id;
    private int $userId;
    private int $productId;
    private int $amount;
    private Product $product;
    private int $totalSum;

    public function getTableName(): string
    {
        return 'user_products';
    }

    public function getAllUserProductsByUserId(int $userId): array|false
    {
        $statement = $this->PDO->query("SELECT * FROM {$this->getTableName()} WHERE user_id = $userId");
        $userProducts = $statement->fetchAll();
        $newUserProducts = [];
        foreach ($userProducts as $userProduct) {
            $newUserProducts[] = $this->createObj($userProduct);
        }
        return $newUserProducts;
    }
    public function isUserHaveProduct(int $userId, int $productId): self|null
    {
        $statement = $this->PDO->query("SELECT * FROM {$this->getTableName()} 
                                                WHERE product_id = $productId AND user_id = $userId");
        $product = $statement->fetch();
        if ($product) {
            return $this->createObj($product);
        }
        return null;

    }
    public function changeProductAmount(int $userId, int $productId, int $amount)
    {
        $statement = $this->PDO->prepare("UPDATE {$this->getTableName()}
                                                SET amount = :amount
                                                WHERE product_id = $productId AND user_id = $userId");
        $statement->execute(['amount' => $amount]);
    }
    public function addProduct(int $userId, int $productId, int $amount)
    {
        $statement = $this->PDO->prepare("INSERT INTO {$this->getTableName()} (user_id, product_id, amount)
                                                VALUES ($userId, $productId, :amount)");
        $statement->execute(['amount' => $amount]);
    }
    public function deleteProduct(int $userId, int $productId)
    {
        $statement = $this->PDO->prepare("DELETE FROM {$this->getTableName()}
                                                WHERE user_id = :userId AND product_id = :productId");
        $statement->execute(['userId' => $userId, 'productId' => $productId]);
    }
    public function deleteByUserId(int $userId)
    {
        $statement = $this->PDO->prepare("DELETE FROM {$this->getTableName()} WHERE user_id = :userId");
        $statement->execute(['userId' => $userId]);
    }

    private function createObj(array $product): self|null
    {
        if(!$product){
            return null;
        }

        $obj = new self();
        $obj->id = $product['id'];
        $obj->userId = $product['user_id'];
        $obj->productId = $product['product_id'];
        $obj->amount = $product['amount'];

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getUserId(): int
    {
        return $this->userId;
    }
    public function getProductId(): int
    {
        return $this->productId;
    }
    public function getAmount(): int
    {
        return $this->amount;
    }
    public function getProduct(): Product
    {
        return $this->product;
    }
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }
    public function setTotalSum(int $totalSum): void
    {
        $this->totalSum = $totalSum;
    }
    public function getTotalSum(): int
    {
        return $this->totalSum;
    }
}