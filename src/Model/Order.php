<?php

namespace Model;

class Order extends Model
{
    private int $id;
    private string $contactName;
    private string $contactPhone;
    private string $comment;
    private int $userId;
    private string $address;
    private int $sum;
    private array $orderProducts;

    public static function getTableName(): string
    {
        return 'orders';
    }

    public static function create(string $name, string $phone, string $comment, string $address, int $userId): int
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare(
            "INSERT INTO $tableName (contact_name, contact_phone, comment, user_id, address)
                    VALUES (:name, :phone, :comment, :user_id, :address) RETURNING id"
        );
        $stmt->execute([
            'name' => $name,
            'phone' => $phone,
            'comment' => $comment,
            'address' => $address,
            'user_id' => $userId
        ]);
        $dataId = $stmt->fetch();
        return $dataId['id'];
    }
    public static function getAllByUserId(int $userId): array
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare("SELECT * FROM $tableName WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $userOrders = $stmt->fetchAll();
        $newUserOrders = [];
        foreach ( $userOrders as  $userOrder) {
            $newUserOrders[] = static::createObj($userOrder, $userOrder['id']);
        }
        return $newUserOrders;
    }
    public static function getAllByUserIdWithProducts(int $userId): array
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare("SELECT * FROM $tableName t1
                                                    INNER JOIN order_products t2 ON t1.id = t2.order_id
                                                    INNER JOIN products t3 ON t2.product_id = t3.id
                                                    WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $userOrders = $stmt->fetchAll();
        $newUserOrders = [];
        foreach ($userOrders as  $userOrder) {
            $newUserOrders[$userOrder['order_id']] = static::createObj($userOrder, $userOrder['order_id']);

            $orderProducts[$userOrder['order_id']][$userOrder['product_id']] = OrderProduct::createObjWithProduct($userOrder, $userOrder[6]);
            $newUserOrders[$userOrder['order_id']]->setOrderProducts($orderProducts[$userOrder['order_id']]);
        }
        return $newUserOrders;
    }

    public static function createObj(array $userOrder, int $id): self|null
    {
        if(!$userOrder){
            return null;
        }

        $obj = new self();
        $obj->id = $id;
        $obj->contactName = $userOrder['contact_name'];
        $obj->contactPhone = $userOrder['contact_phone'];
        $obj->comment = $userOrder['comment'];
        $obj->userId = $userOrder['user_id'];
        $obj->address = $userOrder['address'];

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getContactName(): string
    {
        return $this->contactName;
    }
    public function getContactPhone(): string
    {
        return $this->contactPhone;
    }
    public function getComment(): string
    {
        return $this->comment;
    }
    public function getUserId(): int
    {
        return $this->userId;
    }
    public function getAddress(): string
    {
        return $this->address;
    }
    public function getSum(): int
    {
        return $this->sum;
    }
    public function setSum(int $sum): void
    {
        $this->sum = $sum;
    }
    public function getOrderProducts(): array
    {
        return $this->orderProducts;
    }
    public function setOrderProducts(array $orderProducts): void
    {
        $this->orderProducts = $orderProducts;
    }

}