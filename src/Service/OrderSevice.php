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
    private Order $orderModel;
    private OrderProduct $orderProductModel;
    private UserProduct $userProductModel;
    private AuthInterface $authService;
    private Product $productModel;
    private CartService $cartService;
    public function __construct()
    {
        $this->orderModel = new Order();
        $this->orderProductModel = new OrderProduct();
        $this->userProductModel = new UserProduct();
        $this->authService = new AuthSessionService();
        $this->productModel = new Product();
        $this->cartService = new CartService();
    }
    public function create(OrderCreateDTO $data)
    {
        $sum = $this->cartService->getSum();

        if ($sum < 1000) {
            throw new \Exception('Для оформления заказа сумма корзины должна быть больше 1000');
        }

        $user = $this->authService->getCurrentUser();

        $userProducts = $this->userProductModel->getAllUserProductsByUserId($user->getId());

        $orderId = $this->orderModel->create(
            $data->getName(),
            $data->getPhone(),
            $data->getComment(),
            $data->getAddress(),
            $user->getId()
        );
        foreach ($userProducts as $userProduct)
        {
            $this->orderProductModel->create($orderId, $userProduct->getProductId(), $userProduct->getAmount());
        }
        $this->userProductModel->deleteByUserId($user->getId());
    }

    public function getAll(): array
    {
        $user = $this->authService->getCurrentUser();

        $orders = $this->orderModel->getAllByUserId($user->getId());

        foreach ($orders as $order) {
            $orderProducts = $this->orderProductModel->getAllByOrderId($order->getId());

            $totalSum = 0;
            foreach ($orderProducts as $orderProduct) {
                $product = $this->productModel->getOneById($orderProduct->getProductId());
                $orderProduct->setProduct($product);

                $itemSum = $orderProduct->getAmount() * $product->getPrice();
                $orderProduct->setSum($itemSum);

                $totalSum += $itemSum;
            }

            $order->setOrderProducts($orderProducts);
            $order->setSum($totalSum);
        }
        return $orders;
    }


}