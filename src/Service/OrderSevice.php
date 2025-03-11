<?php

namespace Service;

use DTO\OrderCreateDTO;
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
    public function create(OrderCreateDTO $data)
    {
        $orderProducts = $this->userProductModel->getAllUserProductsByUserId($data->getUser()->getId());
        $orderId = $this->orderModel->create(
            $data->getName(),
            $data->getPhone(),
            $data->getComment(),
            $data->getAddress(),
            $data->getUser()->getId()
        );
        foreach ($orderProducts as $orderProduct)
        {
            $this->orderProductModel->create($orderId, $orderProduct->getProductId(), $orderProduct->getAmount());
        }
        $this->userProductModel->deleteByUserId($data->getUser()->getId());
    }

}