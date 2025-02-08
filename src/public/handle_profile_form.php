<?php

session_start();
if(isset($_SESSION['userId'])){
    $userId = $_SESSION['userId'];
    $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
    $statement = $pdo->query("SELECT * FROM users WHERE id = $userId");
    $data = $statement->fetch();
}
else{
    header('Location: login_form.php');
}

require_once './profile_form.php';
?>