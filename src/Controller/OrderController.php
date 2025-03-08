<?php

namespace Controller;

use Model\OrderProduct;
use Model\UserProduct;
use Model\Product;
use Model\Order;
use Service\OrderSevice;


class OrderController extends BaseController
{
    private UserProduct $userProductModel;
    private Product $productModel;
    private OrderProduct $orderProductModel;
    private Order $orderModel;
    private OrderSevice $orderSevice;
    public function __construct()
    {
        parent::__construct();
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->orderProductModel = new OrderProduct();
        $this->orderModel = new Order();
        $this->orderSevice = new OrderSevice();
    }

    public function getCheckoutForm()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $orderProducts = $this->userProductModel->getAllUserProductsByUserId($user->getId());
            if(empty($orderProducts))
            {
                header('Location: /catalog');
                exit();
            }
            $newOrderProducts = $this->newOrderProducts($orderProducts);
            $total = $this->totalOrderProducts($newOrderProducts);
            require_once '../Views/order_form.php';
        } else {
            header('Location: /login');
            exit();
        }
    }
    public function getAllOrders()
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }
        $user = $this->authService->getCurrentUser();

        $userOrders = $this->orderModel->getAllByUserId($user->getId());

        $newUserOrders = [];

        foreach ($userOrders as $userOrder) {
            $orderProducts = $this->orderProductModel->getAllByOrderId($userOrder->getId());
            $newOrderProducts = $this->newOrderProducts($orderProducts);
            $userOrder->setOrderProducts($newOrderProducts);
            $userOrder->setTotal($this->totalOrderProducts($orderProducts));
            $newUserOrders[] = $userOrder;
        }

        require_once '../Views/user_orders.php';
    }
    public function handleCheckout()
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }

        $data = $_POST;
        $errors = $this->validate($data);
        $user = $this->authService->getCurrentUser();
        $orderProducts = $this->userProductModel->getAllUserProductsByUserId($user->getId());
        $newOrderProducts = $this->newOrderProducts($orderProducts);
        $total = $this->totalOrderProducts($newOrderProducts);

        if (empty($errors)) {
            $this->orderSevice->create($data, $user->getId(), $orderProducts);
            header('Location: /user-orders');
            exit();
        }else{
            require_once '../Views/order_form.php';
        }

    }
    private function validate(array $data): array
    {
        $errors = [];

        if (isset($data['name'])) {
            if (strlen($data['name']) < 2) {
                $errors['name'] = 'Имя пользователя должно быть больше 2 символов';
            } elseif (!preg_match('/^[a-zA-Zа-яА-Я0-9_\-\.]+$/u', $data['name'])) {
                $errors['name'] = "Имя пользователя может содержать только буквы, цифры, символы '_', '-', '.'";
            }
        } else {
            $errors['name'] = 'Введите имя';
        }

        if (isset($data['address'])) {
            if (!preg_match('/^[\d\s\w\.,-]+$/u', $data['address'])) {
                $errors['address'] = "Адрес содержит недопустимые символы";
            }elseif (!preg_match('/[а-яА-ЯёЁ]+\s+\d+/', $data['address'])) {
                $errors['address'] = "Адрес должен содержать номер дома и улицу";
            }
        } else {
            $errors['address'] = 'Введите адрес';
        }

        if (isset($data['phone'])) {
            $cleanedPhone = preg_replace('/\D/', '', $data['phone']);
            if(strlen($cleanedPhone) < 11) {
                $errors['phone'] = 'Номер телефона не может быть меньше 11 символов';
            }elseif (!preg_match('/^\+\d+$/', $data['phone'])) {
                $errors['phone'] = "Номер телефона должен начинаться с '+' и содержать только цифры после него";
            }
        } else {
            $errors['phone'] = 'Введите имя';
        }
        return $errors;
    }
    private function newOrderProducts(array $orderProducts): array
    {
        $newOrderProducts = [];
        foreach ($orderProducts as $orderProduct)
        {
            $product = $this->productModel->getOneById($orderProduct->getProductId());
            $orderProduct->setProduct($product);
            $totalSum = $orderProduct->getAmount() * $orderProduct->getProduct()->getPrice();
            $orderProduct->getProduct()->setTotalSum($totalSum);
            $newOrderProducts[] = $orderProduct;
        }
        return $newOrderProducts;
    }
    private function totalOrderProducts(array $newOrderProducts): int
    {
        $total = 0;
        foreach ($newOrderProducts as $newOrderProduct)
        {
            $total += $newOrderProduct->getProduct()->getTotalSum();
        }
        return $total;
    }
}