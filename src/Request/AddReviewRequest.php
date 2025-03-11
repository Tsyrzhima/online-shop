<?php

namespace Request;

use Model\Product;

class AddReviewRequest extends Request
{
    public function getProductId(): int
    {
        return $this->data['product_id'];
    }
    public function getRating(): int
    {
        return $this->data['rating'];
    }
    public function getReview(): string
    {
        return $this->data['review'];
    }
    public function validate(): ?array
    {
        $errors = [];
        if (isset($this->data['product_id'])) {
            if (!is_numeric($this->data['product_id'])) {
                $errors['product_id'] = "id продукта может содержать только цифры";
            } else {
                $productModel = new Product();
                $product = $productModel->getOneById($this->data['product_id']);
                if (!$product) {
                    $errors['product_id'] = 'id c таким продуктом не существует';
                }
            }
        } else {
            $errors['product_id'] = 'Введите id';
        }
        if (isset($this->data['rating'])) {
            if ($this->data['rating'] < 1) {
                $errors['rating'] = "Оценка не может быть ниже 1";
            }elseif ($this->data['rating'] > 5) {
                $errors['rating'] = "Оценка не может быть выше 5";
            }
        }else{
            $errors['rating'] = "Выберите оценку";
        }
        if (isset($this->data['review'])) {
            if (!preg_match('/^[a-zA-Z0-9\s\p{P}]+$/u', $this->data['review'])) {
                $errors['reviewComment'] = "Комментарий содержит недопустимые символы";
            }
        }else{
            $errors['reviewComment'] = "Комментарий не может быть пустым";
        }
        return $errors;
    }
}