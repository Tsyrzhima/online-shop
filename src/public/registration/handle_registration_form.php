<?php

function validate(array $data) : array
{
    $errors = [];
    // валидация имени
    if (isset($data['name'])) {
        if (strlen($data['name']) < 2) {
            $errors['name'] = 'Имя пользователя должно быть больше 2 символов';
        } elseif (!preg_match('/^[a-zA-Zа-яА-Я0-9_\-\.]+$/u', $data['name'])) {
            $errors['name'] = "Имя пользователя может содержать только буквы, цифры, символы '_', '-', '.'";
        }
    }
    else{
        $errors['name'] = 'Введите имя';
    }
    // валидация email
    if (isset($data['email'])){
        if (strlen($data['email'])<2){
            $errors['email'] = 'email должен быть больше 2 символов';
        }
        elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            $errors['email'] = "некоректный email";
        }
        else{
            $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
            $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $statement->execute(['email' => $data['email']]);
            $user = $statement->fetch();
            if ($user) {
                $errors['email'] = 'пользователь с таким email уже существует';
            }
        }
    }
    else{
        $errors['email'] = 'Введите email';
    }

    // валидация пароля и повтора пароля
    if (isset($data['password'])) {
        if (strlen($data['password']) < 4){
            $errors['password'] = 'Пароль должен быть больше 4 символов';
        }
        elseif (!preg_match('/[a-zA-Z]/u', $data['password'])){
            $errors['password'] = "Пароль должен содержать хотя бы один символ";
        }elseif (!preg_match('/[0-9]/u', $data['password'])){
            $errors['password'] = "Пароль должен содержать хотя бы одну цифру";
        }elseif (isset($data['repassword'])) {
            if ($data['password'] !== $data['repassword']) {
                $errors['repassword'] = 'Пароли не совпадают';
            }
        }
        else{
            $errors['repassword'] = 'Введите повтор пароля';
        }
    }
    else{
        $errors['password'] = 'Введите пароль';
    }
    return $errors;
}

$data = $_POST;
$errors = validate($data);

if(empty($errors)){
    $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
    $statement = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :hashed_password)");
    $statement->execute(['name' => $data['name'], 'email' => $data['email'], 'hashed_password' => $hashed_password]);

    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $statement->execute(['email' => $data['email']]);
    $user = $statement->fetch();
    echo "<pre>";
    print_r($user);
    echo "<pre>";
}
else{
    echo 'Вы не зарегистрированы';
}
require_once './registration/registration_form.php';
?>

