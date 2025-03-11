<?php

namespace Request;

class LoginRequest extends Request
{
    public function getEmail(): string
    {
        return $this->data['email'];
    }
    public function getPassword(): string
    {
        return $this->data['password'];
    }
    public function validate(): array
    {
        $errors = [];
        if (!isset($this->data['email'])) {
            $errors['email'] = 'Введите email';
        }
        if (!isset($this->data['password'])) {
            $errors['password'] = 'Введите пароль';
        }
        return $errors;
    }
}