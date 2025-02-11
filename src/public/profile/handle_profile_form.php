<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(isset($_SESSION['userId'])){
    $userId = $_SESSION['userId'];
    $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
    $statement = $pdo->query("SELECT * FROM users WHERE id = $userId");
    $data = $statement->fetch();
}
else{
    header('Location: login');
    exit();
}

require_once './profile/profile_form.php';
?>