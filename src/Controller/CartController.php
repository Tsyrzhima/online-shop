<?php

namespace Controller;

use Model\UserProduct;
use Model\Product;
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
    public function addProductToCart()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $data = $_POST;
            $errors = $this->validate($data);
            if (empty($errors)) {
                $this->cartService->addProduct($data['product_id'], $user->getId(), $data['amount']);
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
                $this->cartService->decreaceProduct($data['product_id'], $user->getId(), $data['amount']);
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