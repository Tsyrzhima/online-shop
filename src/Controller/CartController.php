<?php

namespace Controller;

use Model\Cart;
use Model\Product;

class CartController extends BaseController
{
    private Cart $cartModel;
    public function __construct()
    {
        parent::__construct();
        $this->cartModel = new Cart();
    }
    public function getCart()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $productById = new Product();
            $newUserProducts = [];
            $userProducts = $this->cartModel->getAllUserProductsByUserId($user->getId());
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
    public function addProductToCart()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $data = $_POST;
            $errors = $this->validate($data);
            if (empty($errors)) {
                $product = $this->cartModel->isUserHaveProduct($user->getId(), $data['product_id']);
                if ($product) {
                    $amount = $product->getAmount() + $data['amount'];
                    $this->cartModel->changeProductAmount($user->getId(), $data['product_id'], $amount);
                } else {
                    $this->cartModel->addProduct($user->getId(), $data['product_id'], $data['amount']);
                }
            }
            header('Location: /catalog');
        } else {
            header('Location: /login');
            exit();
        }
    }
    public function decreaceProductFromCart()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $data = $_POST;
            $errors = $this->validate($data);
            if (empty($errors)) {
                $product = $this->cartModel->isUserHaveProduct($user->getId(), $data['product_id']);
                if ($product) {
                    if($product->getAmount() > 1){
                        $amount = $product->getAmount() - $data['amount'];
                        $this->cartModel->changeProductAmount($user->getId(), $data['product_id'], $amount);
                    }elseif ($product->getAmount() === 1){
                        $this->cartModel->deleteProduct($user->getId(), $data['product_id']);
                    }
                }
            }
            header('Location: /catalog');
        } else {
            header('Location: /login');
            exit();
        }
    }
    private function validate(array $data): array
    {
        $errors = [];

        if (isset($data['product_id'])) {
            if (!is_numeric($data['product_id'])) {
                $errors['product_id'] = "id продукта может содержать только цифры";
            } else {
                $productModel = new Product();
                $product = $productModel->getOneById($data['product_id']);
                if (!$product) {
                    $errors['product_id'] = 'id c таким продуктом не существует';
                }
            }
        } else {
            $errors['product_id'] = 'Введите id';
        }

        if (isset($data['amount'])) {
            if (!is_numeric($data['amount'])) {
                $errors['amount'] = "количество продукта может содержать только цифры";
            }
        } else {
            $errors['amount'] = 'Введите количество';
        }
        return $errors;
    }

}