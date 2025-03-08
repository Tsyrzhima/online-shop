<?php

namespace Service;

use Model\Product;
use Model\UserProduct;

class CartService
{
    private UserProduct $userProductModel;
    public function __construct()
    {
        $this->userProductModel = new UserProduct();
    }
    public function addProduct(int $productId, int $userId, int $amount)
    {
        $product = $this->userProductModel->isUserHaveProduct($userId, $productId);
        if ($product) {
            $amount = $product->getAmount() + $amount;
            $this->userProductModel->changeProductAmount($userId, $productId, $amount);
        } else {
            $this->userProductModel->addProduct($userId, $productId, $amount);
        }
    }
    public function decreaceProduct(int $productId, int $userId, int $amount)
    {
        $product = $this->userProductModel->isUserHaveProduct($userId, $productId);
        if ($product) {
            if($product->getAmount() > 1){
                $amount = $product->getAmount() - $amount;
                $this->userProductModel->changeProductAmount($userId, $productId, $amount);
            }elseif ($product->getAmount() === 1){
                $this->userProductModel->deleteProduct($userId, $productId);
            }
        }
    }
}