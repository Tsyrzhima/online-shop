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

    public function getTableName(): string
    {
        return 'products';
    }

    public function getAll(): array|false
    {
        $statement = $this->PDO->query("SELECT * FROM {$this->getTableName()}");
        $products = $statement->fetchAll();
        $newProducts = [];
        foreach ($products as $product) {
            $newProducts[] = $this->createObj($product);
        }
        return $newProducts;
    }
    public function getOneById(int $productId): self|null
    {
        $statement = $this->PDO->query("SELECT * FROM {$this->getTableName()} WHERE id = $productId");
        $product = $statement->fetch();
        return $this->createObj($product);
    }

    private function createObj(array $product): self|null
    {
        if(!$product){
            return null;
        }

        $obj = new self();
        $obj->id = $product['id'];
        $obj->name = $product['name'];
        $obj->description = $product['description'];
        $obj->price = $product['price'];
        $obj->imageUrl = $product['image_url'];

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