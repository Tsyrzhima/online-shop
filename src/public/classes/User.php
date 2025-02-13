<?php

class User
{
    public function getRegistrate()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if(isset($_SESSION['userId'])){
            header('Location: catalog');
            exit();
        }else{
            require_once './pages/registration_form.php';
        }
    }
    public function getLogin()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if(isset($_SESSION['userId'])){
            header('Location: catalog');
            exit();
        }else{
            require_once './pages/login_form.php';
        }
    }
    public function getEditProfile()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if(!isset($_SESSION['userId'])){
            header('Location: login');
            exit();
        }else{
            // ?????
            echo 'Пользователь залогинен';
            $this->editProfile();
        }
    }

    public function getProfile()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['userId'])) {
            header('Location: login');
            exit();
        } else {
            $this->profile();
        }
    }

    public function registrate()
    {
        $data = $_POST;
        $errors = $this->validateRegistrate($data);

        if (empty($errors)) {
            $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
            $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
            $statement = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :hashed_password)");
            $statement->execute(['name' => $data['name'], 'email' => $data['email'], 'hashed_password' => $hashed_password]);
        } else {
            echo 'Вы не зарегистрированы';
        }
        require_once './pages/registration_form.php';
    }

    private function validateRegistrate(array $data): array
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
                if ($user) {
                    $errors['email'] = 'пользователь с таким email уже существует';
                }
            }
        } else {
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

    public function login()
    {
        $data = $_POST;
        $errors = $this->validateLogin($data);

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
        require_once './pages/login_form.php';
    }

    private function validateLogin(array $data): array
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

    public function editProfile()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $userId = $_SESSION['userId'];
        $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
        $statement = $pdo->query("SELECT * FROM users WHERE id = $userId");
        $data = $statement->fetch();

        $dataNew = $_POST;
        $errors = $this->validateEditProfile($dataNew, $userId);
        $flag = false;

        if (empty($errors)) {

            if(!empty($dataNew['name'])&&($data['name'] !== $dataNew['name'])){
                $this->editDate($dataNew, 'name', $userId);
                $flag = true;
            }
            if(!empty($dataNew['email'])&&($data['email'] !== $dataNew['email'])){
                $this->editDate($dataNew, 'email', $userId);
                $flag = true;
            }
            if(!empty($dataNew['password'])&&(!password_verify($dataNew['password'], $data['password']))){
                $hashed_password = password_hash($dataNew['password'], PASSWORD_DEFAULT);
                $dataNew['password'] = $hashed_password;
                $this->editDate($dataNew, 'password', $userId);
                $flag = true;
            }
            if($flag){
                header('Location: profile');
                exit();
            }
        }
        require_once './pages/edit_profile_form.php';
    }

    private function validateEditProfile(array $data, int $userId) : array
    {
        $errors = [];

        // валидация имени
        if (isset($data['name'])) {
            if(!empty($data['email'])) {
                if (strlen($data['name']) < 2) {
                    $errors['name'] = 'Имя пользователя должно быть больше 2 символов';
                } elseif (!preg_match('/^[a-zA-Zа-яА-Я0-9_\-\.]+$/u', $data['name'])) {
                    $errors['name'] = "Имя пользователя может содержать только буквы, цифры, символы '_', '-', '.'";
                }
            }

        }

        // валидация email
        if (isset($data['email'])) {
            if(!empty($data['email'])) {
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
            }

        }

        // валидация пароля и повтора пароля
        if (isset($data['password'])) {
            if(!empty($data['password'])) {
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

        }
        return $errors;
    }
    private function editDate(array $dataNew, string $column, int $userId){
        $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
        $statement = $pdo->prepare("UPDATE users SET {$column} = :{$column} WHERE id = :id");
        $statement->execute(['id' => $userId, "{$column}" => $dataNew["{$column}"]]);
    }

    public function profile()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $userId = $_SESSION['userId'];
        $pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
        $statement = $pdo->query("SELECT * FROM users WHERE id = $userId");
        $data = $statement->fetch();

        require_once './pages/profile_form.php';

    }

}