<?php

namespace Request;

class HandleCheckoutOrderRequest extends Request
{
    public function getName()
    {
        return $this->data['name'];
    }
    public function getPhone()
    {
        return $this->data['phone'];
    }
    public function getComment()
    {
        return $this->data['comment'];
    }
    public function getAddress()
    {
        return $this->data['address'];
    }
    public function validate(): array
    {
        $errors = [];

        if (isset($this->data['name'])) {
            if (strlen($this->data['name']) < 2) {
                $errors['name'] = 'Имя пользователя должно быть больше 2 символов';
            } elseif (!preg_match('/^[a-zA-Zа-яА-Я0-9_\-\.]+$/u', $this->data['name'])) {
                $errors['name'] = "Имя пользователя может содержать только буквы, цифры, символы '_', '-', '.'";
            }
        } else {
            $errors['name'] = 'Введите имя';
        }

        if (isset($this->data['address'])) {
            if (!preg_match('/^[\d\s\w\.,-]+$/u', $this->data['address'])) {
                $errors['address'] = "Адрес содержит недопустимые символы";
            }elseif (!preg_match('/[а-яА-ЯёЁ]+\s+\d+/', $this->data['address'])) {
                $errors['address'] = "Адрес должен содержать номер дома и улицу";
            }
        } else {
            $errors['address'] = 'Введите адрес';
        }

        if (isset($this->data['phone'])) {
            $cleanedPhone = preg_replace('/\D/', '', $this->data['phone']);
            if(strlen($cleanedPhone) < 11) {
                $errors['phone'] = 'Номер телефона не может быть меньше 11 символов';
            }elseif (!preg_match('/^\+\d+$/', $this->data['phone'])) {
                $errors['phone'] = "Номер телефона должен начинаться с '+' и содержать только цифры после него";
            }
        } else {
            $errors['phone'] = 'Введите имя';
        }
        return $errors;
    }
}