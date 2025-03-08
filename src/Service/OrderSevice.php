<?php

namespace Service;

use Model\Order;
use Model\OrderProduct;
use Model\UserProduct;

class OrderSevice
{
    private Order $orderModel;
    private OrderProduct $orderProductModel;
    private UserProduct $userProductModel;
    public function __construct()
    {
        $this->orderModel = new Order();
        $this->orderProductModel = new OrderProduct();
        $this->userProductModel = new UserProduct();
    }
    public function create(array $data, $userId, array $orderProducts)
    {
        $orderId = $this->orderModel->create($data, $userId);
        foreach ($orderProducts as $orderProduct)
        {
            $this->orderProductModel->create($orderId, $orderProduct->getProductId(), $orderProduct->getAmount());
        }
        $this->userProductModel->deleteByUserId($userId);
    }

}