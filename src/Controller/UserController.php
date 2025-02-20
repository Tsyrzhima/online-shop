<?php

namespace Controller;

class UserController
{
    public function getRegistrate()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (isset($_SESSION['userId'])) {
            header('Location: /catalog');
            exit();
        }else {
            require_once '../Views/registration_form.php';
        }
    }

    public function getLogin()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (isset($_SESSION['userId'])) {
            header('Location: /catalog');
            exit();
        } else {
            require_once '../Views/login_form.php';
        }
    }

    public function getEditProfile()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        } else {
            $this->editProfile();
        }
    }

    public function getProfile()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
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
            $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
            $userModel = new \Model\User();
            $userModel->registrate($data['name'], $data['email'], $hashed_password);
            $userModel = new \Model\User();
            $user = $userModel->getByEmail($data['email']);
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $_SESSION['userId'] = $user['id'];
            header('Location: /catalog');
            exit();
        }
        require_once '../Views/registration_form.php';
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
                $userModel = new \Model\User();
                $user = $userModel->getByEmail($data['email']);
                if ($user) {
                    $errors['email'] = 'пользователь с таким email уже существует';
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
        } else {
            $errors['password'] = 'Введите пароль';
        }
        return $errors;
    }

    public function login()
    {
        $data = $_POST;
        $errors = $this->validateLogin($data);

        if (empty($errors)) {
            $userModel = new \Model\User();
            $user = $userModel->getByEmail($data['email']);
            if (!$user) {
                $errors['autorization'] = 'email или пароль неверный';
            } else {
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
        require_once '../Views/login_form.php';
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
        $userModel = new \Model\User();
        $data = $userModel->getById($userId);

        $dataNew = $_POST;
        $errors = $this->validateEditProfile($dataNew, $userId);
        $flag = false;

        if (empty($errors)) {
            $userModel = new \Model\User();
            if (!empty($dataNew['name']) && ($data['name'] !== $dataNew['name'])) {
                $userModel->updateById($dataNew,'name', $userId);
                $flag = true;
            }
            if (!empty($dataNew['email']) && ($data['email'] !== $dataNew['email'])) {
                $userModel->updateById($dataNew,'email', $userId);
                $flag = true;
            }
            if (!empty($dataNew['password']) && (!password_verify($dataNew['password'], $data['password']))) {
                $hashed_password = password_hash($dataNew['password'], PASSWORD_DEFAULT);
                $dataNew['password'] = $hashed_password;
                $userModel->updateById($dataNew,'password', $userId);
                $flag = true;
            }
            if ($flag) {
                header('Location: profile');
                exit();
            }
        }
        require_once '../Views/edit_profile_form.php';
    }

    private function validateEditProfile(array $data, int $userId): array
    {
        $errors = [];

        // валидация имени
        if (isset($data['name'])) {
            if (!empty($data['email'])) {
                if (strlen($data['name']) < 2) {
                    $errors['name'] = 'Имя пользователя должно быть больше 2 символов';
                } elseif (!preg_match('/^[a-zA-Zа-яА-Я0-9_\-\.]+$/u', $data['name'])) {
                    $errors['name'] = "Имя пользователя может содержать только буквы, цифры, символы '_', '-', '.'";
                }
            }

        }

        // валидация email
        if (isset($data['email'])) {
            if (!empty($data['email'])) {
                if (strlen($data['email']) < 2) {
                    $errors['email'] = 'email должен быть больше 2 символов';
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = "некоректный email";
                } else {
                    $userModel = new \Model\User();
                    $user = $userModel->getByEmail($data['email']);
                    if ($user) {
                        if ($user['id'] !== $userId) {
                            $errors['email'] = 'пользователь с таким email уже существует';
                        }
                    }
                }
            }

        }

        // валидация пароля и повтора пароля
        if (isset($data['password'])) {
            if (!empty($data['password'])) {
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

    public function profile()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['userId'])) {
            header('Location: login');
            exit();
        } else {
            $userId = $_SESSION['userId'];
            $userModel = new \Model\User();
            $data = $userModel->getById($userId);
            require_once '../Views/profile_form.php';
        }
    }
    public function logout()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_destroy();
        header('Location: /login');
        exit();
    }
}