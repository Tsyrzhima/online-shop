<?php

namespace Service;

use DTO\AddProductToCartDTO;
use DTO\DecreaceProductFromCartDTO;
use Model\Product;
use Model\UserProduct;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;

class CartService
{
    private UserProduct $userProductModel;
    private Product $productModel;
    private AuthInterface $authService;
    public function __construct()
    {
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->authService = new AuthSessionService();
    }
    public function addProduct(AddProductToCartDTO $data)
    {
        $user = $this->authService->getCurrentUser();
        $product = $this->userProductModel->isUserHaveProduct($user->getId(), $data->getProductId());
        if ($product) {
            $amount = $product->getAmount() + $data->getAmount();
            $this->userProductModel->changeProductAmount($user->getId(), $data->getProductId(), $amount);
        } else {
            $this->userProductModel->addProduct($user->getId(), $data->getProductId(), $data->getAmount());
        }
    }
    public function decreaceProduct(DecreaceProductFromCartDTO $data)
    {
        $user = $this->authService->getCurrentUser();
        $product = $this->userProductModel->isUserHaveProduct($user->getId(), $data->getProductId());
        if ($product) {
            if($product->getAmount() > 1){
                $amount = $product->getAmount() - $data->getAmount();
                $this->userProductModel->changeProductAmount($user->getId(), $data->getProductId(), $amount);
            }elseif ($product->getAmount() === 1){
                $this->userProductModel->deleteProduct($user->getId(), $data->getProductId());
            }
        }
    }
    public function getUserProducts(): array
    {
        $user = $this->authService->getCurrentUser();

        if($user === null){
            return [];
        }

        $userProducts =  $this->userProductModel->getAllUserProductsByUserId($user->getId());

        foreach ($userProducts as $userProduct)
        {
            $product = $this->productModel->getOneById($userProduct->getProductId());
            $userProduct->setProduct($product);
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