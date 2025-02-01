<?php

// Валидация имени
function validateName($name)
{
    if (empty($name)){
        return 'Введите имя';
    }
    elseif(strlen($name)<2) {
        return 'Имя пользователя должно быть больше 2 символов';
    }
    elseif (!preg_match('/^[a-zA-Zа-яА-Я0-9_\-\.]+$/u', $name)){
        return "Имя пользователя может содержать только буквы, цифры, символы '_', '-', '.'";
    }
}
// Валидация email
function validateEmail($email, $data)
{
    if(empty($email)){
        return 'Введите email';
    }
    elseif (strlen($email)<2){
        return 'email должен быть больше 2 символов';
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return "некоректный email";
    }
    elseif($data){
            return 'пользователь с таким email уже существует';
    }
}

// Валидация пароля
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

// Валидация повтора пароля
function validateRepassword($password, $repassword)
{
    if(empty($repassword)) {
        return 'Введите пароль';
    }
    elseif($password!==$repassword){
        return 'Пароли не совпадают';
    }
}

$pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
$errors = [];

if(isset($_POST['name'])){
    $name = $_POST['name'];
    $res_val = validateName($name);
    if($res_val) {
        $errors['name'] = $res_val;
    }
}
if(isset($_POST['email'])){
    $email = $_POST['email'];
    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $statement->execute(['email' => $email]);
    $data = $statement->fetch();
    $res_val = validateEmail($email, $data);
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
if(isset($_POST['repassword'])){
    $repassword = $_POST['repassword'];
    if(!empty($password)){
        $res_val = validateRepassword($password, $repassword);
        if($res_val){
            $errors['repassword'] = $res_val;
        }
    }
}

if(empty($errors)){
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $statement = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :hashed_password)");
    $statement->execute(['name' => $name, 'email' => $email, 'hashed_password' => $hashed_password]);
    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $statement->execute(['email' => $email]);
    $data = $statement->fetch();
    echo "<pre>";
    print_r($data);
    echo "<pre>";
}
else{
    echo 'Вы не зарегистрированы';
}
require_once './registration_form.php';
?>

