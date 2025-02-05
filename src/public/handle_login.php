<?php


function validateEmail($email)
{
    if(empty($email)){
        return 'Введите email';
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return "некоректный email";
    }
}
function validatePassword($password)
{
    if(empty($password)){
        return 'Введите пароль';
    }
    elseif (strlen($password)<4){
        return 'Пароль должен быть больше 4 символов';
    }
    elseif (!preg_match('/[a-zA-Z]/u', $password)) {
        return "Пароль должен содержать хотя бы один символ";
    }elseif(!preg_match('/[0-9]/u', $password)){
        return "Пароль должен содержать хотя бы одну цифру";
    }
}



$errors = [];

if(isset($_POST['email'])){
    $email = $_POST['email'];
    $res_val = validateEmail($email);
    if($res_val) {
        $errors['email'] = $res_val;
    }
}
if(isset($_POST['password'])){
    $password = $_POST['password'];
    $res_val = validatePassword($password);
    if($res_val) {
        $errors['password'] = $res_val;
    }
}

if(empty($errors)){
    $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $statement->execute(['email' => $email]);
    $user = $statement->fetch();
    if(!$user){
        $errors['autorization'] = 'email или пароль неверный';
    }
    else {
        $passwordDb = $user['password'];
        if (password_verify($password, $passwordDb)) {
            session_start();
            $_SESSION['logged_in'] = true;
            //setcookie('user_id', $user['id']);
            header('Location: catalog.php');
        } else {
            $errors['autorization'] = 'email или пароль неверный';
        }
    }
}
require_once './login_form.php';
?>