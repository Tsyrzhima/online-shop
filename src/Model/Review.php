<?php

namespace Model;

use DateTime;

class Review extends Model
{
    private int $id;
    private int $productId;
    private int $userId;
    private string $date;
    private int $rating;
    private string $reviewComment;
    private User $user;

    public function getTableName(): string
    {
        return 'reviews';
    }
    public function getAllByProductId(int $productId): array|null
    {
        $statement = $this->PDO->query("SELECT * FROM {$this->getTableName()} WHERE product_id = $productId");
        $reviews = $statement->fetchAll();
        $newReviews = [];
        foreach ($reviews as $review) {
            $newReviews[] = $this->createObj($review);
        }
        return $newReviews;
    }
    public function addReview(int $productId, int $userId, string $date, int $rating, string $reviewComment): void
    {
        $stmt = $this->PDO->prepare
        (
            "INSERT INTO {$this->getTableName()}(product_id, user_id, date, rating, review_comment)
                        VALUES (:product_id, :user_id, :date, :rating, :review_comment)"
        );
        $stmt->execute(['product_id' => $productId,
                        'user_id' => $userId,
                        'date' => $date,
                        'rating' => $rating,
                        'review_comment' => $reviewComment
                        ]);
    }
    private function createObj(array $review): self|null
    {
        if(!$review){
            return null;
        }
        $obj = new self();
        $obj->id = $review['id'];
        $obj->productId = $review['product_id'];
        $obj->userId = $review['user_id'];
        $obj->date = $review['date'];
        $obj->rating = $review['rating'];
        $obj->reviewComment = $review['review_comment'];

        return $obj;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getProductId(): int
    {
        return $this->productId;
    }
    public function getUserId(): int
    {
        return $this->userId;
    }
    public function getDate(): string
    {
        return $this->date;
    }
    public function getRating(): int
    {
        return $this->rating;
    }
    public function getReviewComment(): string
    {
        return $this->reviewComment;
    }
    public function getUser(): User
    {
        return $this->user;
    }
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}