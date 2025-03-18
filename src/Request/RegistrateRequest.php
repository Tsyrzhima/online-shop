<?php

namespace Request;

use Model\User;
class RegistrateRequest extends Request
{
    private User $userModel;
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->userModel = new User();
    }
    public function getName(): string
    {
        return $this->data['name'];
    }
    public function getEmail(): string
    {
        return $this->data['email'];
    }
    public function getPassword(): string
    {
        return $this->data['password'];
    }
    public function getRepassword(): string
    {
        return $this->data['repassword'];
    }
    public function validate(): array
    {
        $errors = [];

        // валидация имени
        if (isset($this->data['name'])) {
            if (mb_strlen($this->data['name']) < 2) {
                $errors['name'] = 'Имя пользователя должно быть больше 2 символов';
            } elseif (!preg_match('/^[a-zA-Zа-яА-Я0-9_\-\.]+$/u', $this->data['name'])) {
                $errors['name'] = "Имя пользователя может содержать только буквы, цифры, символы '_', '-', '.'";
            }
        } else {
            $errors['name'] = 'Введите имя';
        }

        // валидация email
        if (isset($this->data['email'])) {
            if (strlen($this->data['email']) < 2) {
                $errors['email'] = 'email должен быть больше 2 символов';
            } elseif (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "некоректный email";
            } else {
                $user = $this->userModel->getByEmail($this->data['email']);
                if ($user) {
                    $errors['email'] = 'пользователь с таким email уже существует';
                }
            }
        } else {
            $errors['email'] = 'Введите email';
        }

        // валидация пароля и повтора пароля
        if (isset($this->data['password'])) {
            if (strlen($this->data['password']) < 4) {
                $errors['password'] = 'Пароль должен быть больше 4 символов';
            } elseif (!preg_match('/[a-zA-Z]/u', $this->data['password'])) {
                $errors['password'] = "Пароль должен содержать хотя бы один символ";
            } elseif (!preg_match('/[0-9]/u', $this->data['password'])) {
                $errors['password'] = "Пароль должен содержать хотя бы одну цифру";
            } elseif (isset($this->data['repassword'])) {
                if ($this->data['password'] !== $this->data['repassword']) {
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

}