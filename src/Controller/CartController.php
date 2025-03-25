<?php

namespace Controller;

use DTO\AddProductToCartDTO;
use DTO\DecreaceProductFromCartDTO;
use Request\AddProductToCartRequest;
use Request\DecreaceProductFromCartRequest;
use Service\CartService;

class CartController extends BaseController
{
    private CartService $cartService;
    public function __construct()
    {
        parent::__construct();
        $this->cartService = new CartService();
    }
    public function getCart()
    {
        if ($this->authService->check()) {
            $userProducts = $this->cartService->getUserProducts();
            require_once '../Views/cart.php';
        } else {
            header('Location: /login');
            exit();
        }
    }
    public function addProductToCart(AddProductToCartRequest $request)
    {
        if ($this->authService->check()) {
            $errors = $request->validate();
            if (empty($errors)) {
                $dto = new AddProductToCartDTO($request->getProductId(), $request->getAmount());
                $amount = $this->cartService->addProduct($dto);
                return response()->json(['amount'=>$amount]);
            };
        };
    }
    public function decreaceProductFromCart(DecreaceProductFromCartRequest $request)
    {
        if ($this->authService->check()) {
            $errors = $request->validate();
            if (empty($errors)) {
                $dto = new DecreaceProductFromCartDTO($request->getProductId(), $request->getAmount());
                $this->cartService->decreaceProduct($dto);
            }

        }
    }


}