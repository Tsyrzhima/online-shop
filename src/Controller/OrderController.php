<?php

namespace Controller;

use DTO\OrderCreateDTO;
use Request\HandleCheckoutOrderRequest;
use Service\CartService;
use Service\OrderSevice;


class OrderController extends BaseController
{
    private OrderSevice $orderSevice;
    private CartService $cartService;
    public function __construct()
    {
        parent::__construct();
        $this->orderSevice = new OrderSevice();
        $this->cartService = new CartService();
    }
    public function getCheckoutForm()
    {
        if ($this->authService->check()) {
            $userProducts = $this->cartService->getUserProducts();
            if(empty($userProducts))
            {
                header('Location: /catalog');
                exit();
            }
            $total = $this->cartService->getSum();
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

        $userOrders = $this->orderSevice->getAll();

        require_once '../Views/user_orders.php';
    }
    public function handleCheckout(HandleCheckoutOrderRequest $request)
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }

        $errors = $request->validate();

        if (empty($errors)) {
            $dto = new OrderCreateDTO($request->getName(), $request->getPhone(), $request->getComment(), $request->getAddress());

            $this->orderSevice->create($dto);
            header('Location: /user-orders');
            exit();
        }else{
            $userProducts =  $this->cartService->getUserProducts();
            $total = $this->cartService->getSum();

            require_once '../Views/order_form.php';
        }

    }

}