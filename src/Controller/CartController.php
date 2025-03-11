<?php

namespace Controller;

use DTO\AddProductToCartDTO;
use DTO\DecreaceProductFromCartDTO;
use Model\UserProduct;
use Model\Product;
use Request\AddProductToCartRequest;
use Request\DecreaceProductFromCartRequest;
use Service\CartService;

class CartController extends BaseController
{
    private UserProduct $userProductModel;
    private CartService $cartService;
    public function __construct()
    {
        parent::__construct();
        $this->userProductModel = new UserProduct();
        $this->cartService = new CartService();
    }
    public function getCart()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $productById = new Product();
            $newUserProducts = [];
            $userProducts = $this->userProductModel->getAllUserProductsByUserId($user->getId());
            foreach ($userProducts as $userProduct)
            {
                $product = $productById->getOneById($userProduct->getProductId());
                $userProduct->setProduct($product);
                $newUserProducts[] = $userProduct;
            }
            require_once '../Views/cart.php';
        } else {
            header('Location: /login');
            exit();
        }
    }
    public function addProductToCart(AddProductToCartRequest $request)
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $errors = $request->validate();
            if (empty($errors)) {
                $dto = new AddProductToCartDTO($request->getProductId(), $user, $request->getAmount());
                $this->cartService->addProduct($dto);
            }
            header('Location: /catalog');
        } else {
            header('Location: /login');
            exit();
        }
    }
    public function decreaceProductFromCart(DecreaceProductFromCartRequest $request)
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $errors = $request->validate();
            if (empty($errors)) {
                $dto = new DecreaceProductFromCartDTO($request->getProductId(), $user, $request->getAmount());
                $this->cartService->decreaceProduct($dto);
            }
            header('Location: /catalog');
        } else {
            header('Location: /login');
            exit();
        }
    }


}