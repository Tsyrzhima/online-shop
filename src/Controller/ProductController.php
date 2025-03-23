<?php

namespace Controller;

use Model\Review;
use Model\UserProduct;
use Model\Product;
use Model\User;
use Request\AddReviewRequest;

class ProductController extends BaseController
{
    public function getProducts()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $products = Product::getAll();
            foreach ($products as $product) {
                $cartProduct = UserProduct::isUserHaveProduct($user->getId(), $product->getId());
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

            $product = Product::getOneById($productId);

            $reviews = Review::getAllByProductId($productId);

            $newReviews = [];
            $sumReviews = 0;
            $count = count($reviews);

            foreach($reviews as $review)
            {
                $userIdReview = $review->getUserId();
                $userReview = User::getById($userIdReview);
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
    public function addReview(AddReviewRequest $request)
    {
        if ($this->authService->check()) {
            $productId = $request->getProductId();
            $user = $this->authService->getCurrentUser();
            $date = date("Y-m-d");
            $rating = $request->getRating(); // добавить валидацию
            $reviewComment = $request->getReview();
            $errors = $request->validate();
            if (empty($errors)) {
                Review::add($productId, $user->getId(), $date, $rating, $reviewComment);
            }
            $this->getProduct(); // лучше редирект
            } else {
                header('Location: login');
                exit;
            }
    }


}