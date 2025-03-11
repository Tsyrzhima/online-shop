<?php

namespace Controller;

use DTO\OrderCreateDTO;
use Model\OrderProduct;
use Model\UserProduct;
use Model\Product;
use Model\Order;
use Request\HandleCheckoutOrderRequest;
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
            $userOrder->setTotal($this->totalOrderProducts($newOrderProducts));
            $newUserOrders[] = $userOrder;
        }

        require_once '../Views/user_orders.php';
    }
    public function handleCheckout(HandleCheckoutOrderRequest $request)
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }
        $errors = $request->validate();
        $user = $this->authService->getCurrentUser();
        $orderProducts = $this->userProductModel->getAllUserProductsByUserId($user->getId());
        $newOrderProducts = $this->newOrderProducts($orderProducts);
        $total = $this->totalOrderProducts($newOrderProducts);

        if (empty($errors)) {
            $dto = new OrderCreateDTO($request->getName(), $request->getPhone(), $request->getComment(), $request->getAddress(), $user);
            $this->orderSevice->create($dto);
            header('Location: /user-orders');
            exit();
        }else{
            require_once '../Views/order_form.php';
        }

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