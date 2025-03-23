<?php

namespace Request;

use Model\User;
class EditProfileRequest extends Request
{

    public function getName(): string
    {
        return $this->data['name'];
    }
    public function getEmail(): string
    {
        return $this->data['email'];
    }
    public function getPassword(): ?string
    {
        if(isset($this->data['password'])){
            return $this->data['password'];
        }
        return null;
    }
    public function validate(User $user): array
    {
        $errors = [];

        // валидация имени
        if (isset($this->data['name'])) {
            if (!empty($this->data['email'])) {
                if (strlen($this->data['name']) < 2) {
                    $errors['name'] = 'Имя пользователя должно быть больше 2 символов';
                } elseif (!preg_match('/^[a-zA-Zа-яА-Я0-9_\-\.]+$/u', $this->data['name'])) {
                    $errors['name'] = "Имя пользователя может содержать только буквы, цифры, символы '_', '-', '.'";
                }
            }

        }

        // валидация email
        if (isset($this->data['email'])) {
            if (!empty($this->data['email'])) {
                if (strlen($this->data['email']) < 2) {
                    $errors['email'] = 'email должен быть больше 2 символов';
                } elseif (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = "некоректный email";
                } else {
                    $userDb = User::getByEmail($this->data['email']);
                    if ($userDb) {
                        if ($user->getId() !== $userDb->getId()) {
                            $errors['email'] = 'пользователь с таким email уже существует';
                        }
                    }
                }
            }

        }

        // валидация пароля и повтора пароля
        if (isset($this->data['password'])) {
            if (!empty($this->data['password'])) {
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
            }

        }
        return $errors;
    }
}