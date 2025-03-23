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

    public static function getTableName(): string
    {
        return 'user_products';
    }

    public static function getAllByUserId(int $userId): array|false
    {
        $tableName = static::getTableName();
        $statement = static::getPDO()->query("SELECT * FROM $tableName WHERE user_id = $userId");
        $userProducts = $statement->fetchAll();
        $newUserProducts = [];
        foreach ($userProducts as $userProduct) {
            $newUserProducts[] = static::createObj($userProduct);
        }
        return $newUserProducts;
    }
    public static function getAllByUserIdWithProducts(int $userId): array|false
    {
        $tableName = static::getTableName();
        $statement = static::getPDO()->query("SELECT * FROM $tableName t1
                                                INNER JOIN products t2 ON t1.product_id = t2.id 
                                                WHERE t1.user_id = $userId");
        $userProducts = $statement->fetchAll();
        $newUserProducts = [];
        foreach ($userProducts as $userProduct) {
            $newUserProducts[] = static::createObjWithProduct($userProduct);
        }
        return $newUserProducts;
    }
    public static function isUserHaveProduct(int $userId, int $productId): self|null
    {
        $tableName = static::getTableName();
        $statement = static::getPDO()->query("SELECT * FROM $tableName 
                                                WHERE product_id = $productId AND user_id = $userId");
        $userProduct = $statement->fetch();
        if ($userProduct) {
            return static::createObj($userProduct);
        }
        return null;

    }
    public static function changeProductAmount(int $userId, int $productId, int $amount)
    {
        $tableName = static::getTableName();
        $statement = static::getPDO()->prepare("UPDATE $tableName
                                                SET amount = :amount
                                                WHERE product_id = $productId AND user_id = $userId");
        $statement->execute(['amount' => $amount]);
    }
    public static function addProduct(int $userId, int $productId, int $amount)
    {
        $tableName = static::getTableName();
        $statement = static::getPDO()->prepare("INSERT INTO $tableName (user_id, product_id, amount)
                                                VALUES ($userId, $productId, :amount)");
        $statement->execute(['amount' => $amount]);
    }
    public static function deleteProduct(int $userId, int $productId)
    {
        $tableName = static::getTableName();
        $statement = static::getPDO()->prepare("DELETE FROM $tableName
                                                WHERE user_id = :userId AND product_id = :productId");
        $statement->execute(['userId' => $userId, 'productId' => $productId]);
    }
    public static function deleteByUserId(int $userId)
    {
        $tableName = static::getTableName();
        $statement = static::getPDO()->prepare("DELETE FROM $tableName WHERE user_id = :userId");
        $statement->execute(['userId' => $userId]);
    }

    public static function createObjWithProduct(array $userProduct): self|null
    {
        if(!$userProduct){
            return null;
        }

        $obj = static::createObj($userProduct);

        $product = Product::createObj($userProduct, $userProduct['product_id']);
        $obj->setProduct($product);

        return $obj;
    }
    public static function createObj(array $userProduct): self|null
    {
        if(!$userProduct){
            return null;
        }

        $obj = new self();
        $obj->id = $userProduct['id'];
        $obj->userId = $userProduct['user_id'];
        $obj->productId = $userProduct['product_id'];
        $obj->amount = $userProduct['amount'];

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