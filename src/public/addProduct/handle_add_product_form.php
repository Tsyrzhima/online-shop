<?php

function validate(array $data): array
{
    $errors = [];
    // валидация id
    if (isset($data['product_id'])) {
        if (!is_numeric($data['product_id'])) {
            $errors['product_id'] = "id продукта может содержать только цифры";
        }else{
            $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
            $statement = $pdo->prepare("SELECT * FROM products WHERE id = :product_id");
            $statement->execute(['product_id' => $data['product_id']]);
            $product = $statement->fetch();
            if (!$product) {
                $errors['product_id'] = 'id c таким продуктом не существует';
            }
        }
    }
    else{
        $errors['product_id'] = 'Введите id';
    }
    // валидация amount
    if (isset($data['amount'])) {
        if (!is_numeric($data['amount'])) {
            $errors['amount'] = "количество продукта может содержать только цифры";
        }
    }else {
        $errors['amount'] = 'Введите количество';
    }

    return $errors;
}
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if(isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    $data = $_POST;
    $errors = validate($data);
    if (empty($errors)) {
        $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
        $statement = $pdo->prepare("SELECT * FROM user_products WHERE product_id = :product_id AND user_id = $userId");
        $statement->execute(['product_id' => $data['product_id']]);
        $product = $statement->fetch();
        if ($product) {
            $amount = $product['amount'] + $data['amount'];
            $statement = $pdo->prepare("UPDATE user_products SET amount = :amount WHERE product_id = :product_id AND user_id = $userId");
            $statement->execute(['product_id' => $data['product_id'], 'amount' => $amount]);
            echo "Количество продуктов увеличено";
        }else{
            $statement = $pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES ($userId, :product_id, :amount)");
            $statement->execute(['product_id' => $data['product_id'], 'amount' => $data['amount']]);
            echo "Продукт добавлен в корзину";
        }
    }
    else {
        echo 'Исправьте ошибки';
    }
}else{
    header('Location: login');
    exit();
}

require_once './addProduct/add_product_form.php';
