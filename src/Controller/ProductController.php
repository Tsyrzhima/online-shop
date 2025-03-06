<?php

namespace Controller;

use Model\Cart;
use Model\Product;

class ProductController extends BaseController
{
    public function getProducts()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $productModel = new Product();
            $products = $productModel->getAll();
            $cartModel = new Cart();
            foreach ($products as $product) {
                $cartProduct = $cartModel->isUserHaveProduct($user->getId(), $product->getId());
                if($cartProduct){
                    $product->setAmount($cartProduct->getAmount());
                }else{
                    $product->setAmount(0);
                }
            }
            require_once '../Views/catalog.php';
        } else {
            header('Location: login');
        }
    }


}