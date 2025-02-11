<?php

function validate(array $data): array|null
{
    $errors = [];
    if (!isset($data['email'])) {
        $errors['email'] = 'Введите email';
    }
    if (!isset($data['password'])) {
        $errors['password'] = 'Введите пароль';
    }
    return $errors;
}

$data = $_POST;
$errors = validate($data);

if(empty($errors)){
    $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $statement->execute(['email' => $data['email']]);
    $user = $statement->fetch();
    if(!$user){
        $errors['autorization'] = 'email или пароль неверный';
    }
    else {
        $passwordDb = $user['password'];
        if (password_verify($data['password'], $passwordDb)) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $_SESSION['userId'] = $user['id'];
            //setcookie('userId', $user['id']);
            header('Location: catalog');
            exit();
        } else {
            $errors['autorization'] = 'email или пароль неверный';
        }
    }
}
require_once './login/login_form.php';
?>