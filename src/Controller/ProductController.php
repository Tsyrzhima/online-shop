<?php

namespace Controller;

use Model\Review;
use Model\UserProduct;
use Model\Product;
use Model\User;

class ProductController extends BaseController
{
    private Product $productModel;
    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
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
    public function getReviews()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $productId = $_POST['product_id'];

            $product = $this->productModel->getOneById($productId);

            $reviewModel = new Review();
            $reviews = $reviewModel->getAllByProductId($productId);

            $newReviews = [];
            $sumReviews = 0;
            $count = count($reviews);

            foreach($reviews as $review)
            {
                $userIdReview = $review->getUserId();
                $userModel = new User();
                $userReview = $userModel->getById($userIdReview);
                $review->setName($userReview->getName());
                $sumReviews += $review->getRating();
                $newReviews[] = $review;
            }
            if ($count > 0) {
                $product->setRating($sumReviews/ $count);
                $product->setCount($count);
            }
            require_once '../Views/productReviews.php';
        } else {
            header('Location: login');
            exit;
        }
    }
    public function addReview()
    {
        if ($this->authService->check()) {
            $productId = $_POST['product_id'];
            $user = $this->authService->getCurrentUser();
            $date = date("Y-m-d");
            $rating = $_POST['rating'];
            $reviewComment = $_POST['review'];
            $reviewModel = new Review();
            $reviewModel->addReview($productId, $user->getId(), $date, $rating, $reviewComment);
            $this->getReviews();
            } else {
                header('Location: login');
                exit;
            }
    }

}