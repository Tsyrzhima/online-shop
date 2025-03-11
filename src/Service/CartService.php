<?php

namespace Service;

use DTO\AddProductToCartDTO;
use DTO\DecreaceProductFromCartDTO;
use Model\UserProduct;

class CartService
{
    private UserProduct $userProductModel;
    public function __construct()
    {
        $this->userProductModel = new UserProduct();
    }
    public function addProduct(AddProductToCartDTO $data)
    {
        $product = $this->userProductModel->isUserHaveProduct($data->getUser()->getId(), $data->getProductId());
        if ($product) {
            $amount = $product->getAmount() + $data->getAmount();
            $this->userProductModel->changeProductAmount($data->getUser()->getId(), $data->getProductId(), $amount);
        } else {
            $this->userProductModel->addProduct($data->getUser()->getId(), $data->getProductId(), $data->getAmount());
        }
    }
    public function decreaceProduct(DecreaceProductFromCartDTO $data)
    {
        $product = $this->userProductModel->isUserHaveProduct($data->getUser()->getId(), $data->getProductId());
        if ($product) {
            if($product->getAmount() > 1){
                $amount = $product->getAmount() - $data->getAmount();
                $this->userProductModel->changeProductAmount($data->getUser()->getId(), $data->getProductId(), $amount);
            }elseif ($product->getAmount() === 1){
                $this->userProductModel->deleteProduct($data->getUser()->getId(), $data->getProductId());
            }
        }
    }
}