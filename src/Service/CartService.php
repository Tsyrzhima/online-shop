<?php

namespace Service;

use DTO\AddProductToCartDTO;
use DTO\DecreaceProductFromCartDTO;
use Model\UserProduct;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;

class CartService
{
    private AuthInterface $authService;
    public function __construct()
    {
        $this->authService = new AuthSessionService();
    }
    public function addProduct(AddProductToCartDTO $data): int
    {
        $user = $this->authService->getCurrentUser();
        $product = UserProduct::isUserHaveProduct($user->getId(), $data->getProductId());
        if ($product) {
            $amount = $product->getAmount() + $data->getAmount();
            UserProduct::changeProductAmount($user->getId(), $data->getProductId(), $amount);
        } else {
            $amount = $data->getAmount();
            UserProduct::addProduct($user->getId(), $data->getProductId(), $data->getAmount());
        }
        return $amount;
    }
    public function decreaceProduct(DecreaceProductFromCartDTO $data): int
    {
        $user = $this->authService->getCurrentUser();
        $product = UserProduct::isUserHaveProduct($user->getId(), $data->getProductId());
        if ($product) {
            if($product->getAmount() > 1){
                $amount = $product->getAmount() - $data->getAmount();
                UserProduct::changeProductAmount($user->getId(), $data->getProductId(), $amount);
            }elseif ($product->getAmount() === 1){
                $amount = 0;
                UserProduct::deleteProduct($user->getId(), $data->getProductId());
            }
        }
        return $amount;
    }
    public function getUserProducts(): array
    {
        $user = $this->authService->getCurrentUser();

        if($user === null){
            return [];
        }

        $userProducts =  UserProduct::getAllByUserIdWithProducts($user->getId());

        foreach ($userProducts as $userProduct)
        {
            $totalSum = $userProduct->getAmount() * $userProduct->getProduct()->getPrice();
            $userProduct->setTotalSum($totalSum);
        }
        return $userProducts;
    }
    public function getSum(): int
    {
        $total = 0;
        foreach ($this->getUserProducts() as $userProduct)
        {
            $total += $userProduct->getTotalSum();
        }
        return $total;
    }
}