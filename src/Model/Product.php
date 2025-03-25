<?php

namespace Model;

require_once '../Model/Model.php';
class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private int $price;
    private string $imageUrl;
    private int $amount;
    private float $rating;
    private int $count;

    public static function getTableName(): string
    {
        return 'products';
    }

    public static function getAll(): array|false
    {
        $tableName = static::getTableName();
        $statement = static::getPDO()->query("SELECT * FROM $tableName");
        $products = $statement->fetchAll();
        $newProducts = [];
        foreach ($products as $product) {
            $newProducts[] = static::createObj($product);
        }
        return $newProducts;
    }
    public static function getOneById(int $productId): self|null
    {
        $tableName = static::getTableName();
        $statement = static::getPDO()->query("SELECT * FROM $tableName WHERE id = $productId");
        $product = $statement->fetch();
        return static::createObj($product);
    }

    public static function createObj(array $data, int $id = null): self|null
    {
        if(!$data){
            return null;
        }

        $obj = new self();

        if ($id !== null) {
            $obj->id = $id;
        }else{
            $obj->id = $data['id'];
        }

        $obj->name = $data['name'];
        $obj->description = $data['description'];
        $obj->price = $data['price'];
        $obj->imageUrl = $data['image_url'];

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getPrice(): int
    {
        return $this->price;
    }
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount)
    {
        $this->amount = $amount;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function setRating(float $rating): void
    {
        $this->rating = $rating;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }


}