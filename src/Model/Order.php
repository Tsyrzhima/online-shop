<?php

namespace Model;

class Order extends Model
{
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

    public function getAllById($userId): array
    {
        $stmt = $this->PDO->prepare('SELECT * FROM orders WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

}