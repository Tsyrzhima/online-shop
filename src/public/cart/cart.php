<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if(isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
    $statement = $pdo->query("SELECT * FROM user_products WHERE user_id = $userId");
    $userProducts = $statement->fetchAll();
    foreach ($userProducts as $userProduct) {
        $statement = $pdo->query("SELECT * FROM products WHERE id = $userProduct[product_id]");
        $products = $statement->fetch();
        $userProduct['name'] = $products['name'];
        $userProduct['price'] = $products['price'];
        $userProduct['description'] = $products['description'];
        $userProduct['image_url'] = $products['image_url'];
        $newUserProducts[] = $userProduct;
    }
    require_once './cart/cart_page.php';
}
else{
    header('Location: login');
}
?>