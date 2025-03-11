<?php

namespace DTO;

use Model\User;

class DecreaceProductFromCartDTO
{
    public function __construct(
        private int $product_id,
        private User $user,
        private int $amount
    ){
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }
    public function getUser(): User
    {
        return $this->user;
    }
    public function getAmount(): int
    {
        return $this->amount;
    }
}