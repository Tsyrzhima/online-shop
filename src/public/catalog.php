<?php
session_start();

//if(!isset($_COOKIE['userId'])){
//    header('Location: login_form.php');
//}
if(isset($_SESSION['userId'])) {
    $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
    $statement = $pdo->query("SELECT * FROM products");
    $products = $statement->fetchAll();
    require_once './catalog_page.php';
}
else{
    header('Location: login_form.php');
}
?>
