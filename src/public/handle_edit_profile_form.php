<?php
function validate(array $data, int $userId) : array
{
    $errors = [];

    // валидация имени
    if (isset($data['name'])) {
        if (strlen($data['name']) < 2) {
            $errors['name'] = 'Имя пользователя должно быть больше 2 символов';
        } elseif (!preg_match('/^[a-zA-Zа-яА-Я0-9_\-\.]+$/u', $data['name'])) {
            $errors['name'] = "Имя пользователя может содержать только буквы, цифры, символы '_', '-', '.'";
        }
    } else {
        $errors['name'] = 'Введите имя';
    }

    // валидация email
    if (isset($data['email'])) {
        if (strlen($data['email']) < 2) {
            $errors['email'] = 'email должен быть больше 2 символов';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "некоректный email";
        } else {
            $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
            $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $statement->execute(['email' => $data['email']]);
            $user = $statement->fetch();
            if($user){
                if($user['id'] !== $userId) {
                    $errors['email'] = 'пользователь с таким email уже существует';
                }
            }
        }
    } else {
        $errors['email'] = 'Введите email';
    }

    // валидация пароля и повтора пароля
    if (isset($data['password'])) {
        if (strlen($data['password']) < 4) {
            $errors['password'] = 'Пароль должен быть больше 4 символов';
        } elseif (!preg_match('/[a-zA-Z]/u', $data['password'])) {
            $errors['password'] = "Пароль должен содержать хотя бы один символ";
        } elseif (!preg_match('/[0-9]/u', $data['password'])) {
            $errors['password'] = "Пароль должен содержать хотя бы одну цифру";
        } elseif (isset($data['repassword'])) {
            if ($data['password'] !== $data['repassword']) {
                $errors['repassword'] = 'Пароли не совпадают';
            }
        } else {
            $errors['repassword'] = 'Введите повтор пароля';
        }
    }
    else{
        $errors['password'] = 'Введите пароль';
    }
    return $errors;
}

function editDate(array $dataNew, string $column, int $userId){
    $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
    $statement = $pdo->prepare("UPDATE users SET {$column} = :{$column} WHERE id = :id");
    $statement->execute(['id' => $userId, "{$column}" => $dataNew["{$column}"]]);
    echo "{$column} changed"."<br>";
}

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

$dataNew = $_POST;
$errors = validate($dataNew, $userId);

if (empty($errors)) {

    if($data['name'] !== $dataNew['name']){
        editDate($dataNew, 'name', $userId);
    }
    else{
        echo 'name unchanged'."<br>";
    }
    if($data['email'] !== $dataNew['email']){
        editDate($dataNew, 'email', $userId);
    }
    else {
        echo 'email unchanged'."<br>";
    }
    if(!password_verify($dataNew['password'], $data['password'])){
        $hashed_password = password_hash($dataNew['password'], PASSWORD_DEFAULT);
        $dataNew['password'] = $hashed_password;
        editDate($dataNew, 'password', $userId);
    }
    else {
        echo 'password unchanged'."<br>";
    }
}


require_once './edit_profile_form.php';
?>