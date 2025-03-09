<?php

namespace Controller;

use Model\Review;
use Model\UserProduct;
use Model\Product;
use Model\User;

class ProductController extends BaseController
{
    private Product $productModel;
    private Review $reviewModel;
    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->reviewModel = new Review();
    }
    public function getProducts()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $products = $this->productModel->getAll();
            $userProductModel = new UserProduct();
            foreach ($products as $product) {
                $cartProduct = $userProductModel->isUserHaveProduct($user->getId(), $product->getId());
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
    public function getProduct()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $productId = $_POST['product_id'];

            $product = $this->productModel->getOneById($productId);

            $reviews = $this->reviewModel->getAllByProductId($productId);

            $newReviews = [];
            $sumReviews = 0;
            $count = count($reviews);
            $userModel = new User();

            foreach($reviews as $review)
            {
                $userIdReview = $review->getUserId();
                $userReview = $userModel->getById($userIdReview);
                $review->setUser($userReview);
                $sumReviews += $review->getRating();
                $newReviews[] = $review;
            }
            if ($count > 0) {
                $product->setRating($sumReviews/ $count);
                $product->setCount($count);
            }
            require_once '../Views/product.php';
        } else {
            header('Location: login');
            exit;
        }
    }
    public function addReview()
    {
        if ($this->authService->check()) {
            $data = $_POST;
            $productId = $data['product_id'];
            $user = $this->authService->getCurrentUser();
            $date = date("Y-m-d");
            $rating = $data['rating']; // добавить валидацию
            $reviewComment = $data['review'];
            $errors = $this->validate($productId, $rating, $reviewComment);
            if (empty($errors)) {
                $this->reviewModel->addReview($productId, $user->getId(), $date, $rating, $reviewComment);
            }
            $this->getProduct(); // лучше редирект
            } else {
                header('Location: login');
                exit;
            }
    }
    private function validate(int $productId, int $rating, string $reviewComment): ?array
    {
        $errors = [];
        if (isset($productId)) {
            if (!is_numeric($productId)) {
                $errors['product_id'] = "id продукта может содержать только цифры";
            } else {
                $productModel = new Product();
                $product = $productModel->getOneById($productId);
                if (!$product) {
                    $errors['product_id'] = 'id c таким продуктом не существует';
                }
            }
        } else {
            $errors['product_id'] = 'Введите id';
        }
        if (isset($rating)) {
            if ($rating < 1) {
                $errors['rating'] = "Оценка не может быть ниже 1";
            }elseif ($rating > 5) {
                $errors['rating'] = "Оценка не может быть выше 5";
            }
        }else{
            $errors['rating'] = "Выберите оценку";
        }
        if (isset($reviewComment)) {
            if (!preg_match('/^[a-zA-Z0-9\s\p{P}]+$/u', $reviewComment)) {
                $errors['reviewComment'] = "Комментарий содержит недопустимые символы";
            }
        }else{
            $errors['reviewComment'] = "Комментарий не может быть пустым";
        }
        return $errors;
    }

}