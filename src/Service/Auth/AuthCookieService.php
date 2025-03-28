<?php

namespace Service\Auth;

use DTO\AuthenticationDTO;
use Model\User;

class AuthCookieService implements AuthInterface
{
    public function check(): bool
    {
        return isset($_COOKIE['userId']);
    }
    public function getCurrentUser(): ?User
    {
        if($this->check()){
            $userId = $_COOKIE['userId'];
            $user = User::getById($userId);
            return $user;
        }
        else{
            return null;
        }
    }
    public function auth(AuthenticationDTO $data): bool
    {
        $user = User::getByEmail($data->getEmail());
        if (!$user) {
            return false;
        } else {
            $passwordDb = $user->getPassword();
            if (password_verify($data->getPassword(), $passwordDb)) {
                setcookie('userId', $user->getId());
                return true;
            } else {
                return false;
            }
        }
    }
    public function logout()
    {
        setcookie('userId', '', time() - 3600, '/');
        unset($_COOKIE['userId']);
    }

}