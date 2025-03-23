<?php

namespace Service;

use DTO\OrderCreateDTO;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProduct;
use mysql_xdevapi\Exception;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;

class OrderSevice
{
    private AuthInterface $authService;
    private CartService $cartService;
    public function __construct()
    {
        $this->authService = new AuthSessionService();
        $this->cartService = new CartService();
    }
    public function create(OrderCreateDTO $data)
    {
        $sum = $this->cartService->getSum();

        if ($sum < 1000) {
            throw new \Exception('Для оформления заказа сумма корзины должна быть больше 1000');
        }

        $user = $this->authService->getCurrentUser();

        $userProducts = UserProduct::getAllByUserId($user->getId());

        $orderId = Order::create(
            $data->getName(),
            $data->getPhone(),
            $data->getComment(),
            $data->getAddress(),
            $user->getId()
        );
        foreach ($userProducts as $userProduct)
        {
            OrderProduct::create($orderId, $userProduct->getProductId(), $userProduct->getAmount());
        }
        UserProduct::deleteByUserId($user->getId());
    }

    public function getAll(): array
    {
        $user = $this->authService->getCurrentUser();

        $orders = Order::getAllByUserIdWithProducts($user->getId());

        foreach ($orders as $order) {
            $totalSum = 0;
            foreach ($order->getOrderProducts() as $orderProduct) {
                $totalSum += $orderProduct->getSum();
            }
            $order->setSum($totalSum);
        }
        return $orders;
    }


}