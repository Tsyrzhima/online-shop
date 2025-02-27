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
    private Product $product;
    private int $total;
    public function create(array $data, int $userId): int
    {
        $stmt = $this->PDO->prepare(
            "INSERT INTO orders (contact_name, contact_phone, comment, user_id, address)
                    VALUES (:name, :phone, :comment, :user_id, :address) RETURNING id"
        );
        $stmt->execute([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'comment' => $data['comment'],
            'address' => $data['address'],
            'user_id' => $userId
        ]);
        $dataId = $stmt->fetch();
        return $dataId['id'];
    }

    public function getAllByUserId($userId): array
    {
        $stmt = $this->PDO->prepare('SELECT * FROM orders WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $userOrders = $stmt->fetchAll();
        $newUserOrders = [];
        foreach ( $userOrders as  $userOrder) {
            $newUserOrders[] = $this->createObj($userOrder);
        }
        return $newUserOrders;
    }

    private function createObj(array $userOrder): self|null
    {
        if(!$userOrder){
            return null;
        }

        $obj = new self();
        $obj->id = $userOrder['id'];
        $obj->contactName = $userOrder['contact_name'];
        $obj->contactPhone = $userOrder['contact_phone'];
        $obj->comment = $userOrder['comment'];
        $obj->userId = $userOrder['user_id'];
        $obj->address = $userOrder['address'];

        return $obj;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
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

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

}