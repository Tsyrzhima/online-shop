<?php

namespace DTO;

use Model\User;

class DecreaceProductFromCartDTO
{
    public function __construct(
        private int $product_id,
        private int $amount
    ){
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }
    public function getAmount(): int
    {
        return $this->amount;
    }
}