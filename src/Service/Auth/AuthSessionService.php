<?php

namespace Service\Auth;

use DTO\AuthenticationDTO;
use Model\User;

class AuthSessionService implements AuthInterface
{
    public function check(): bool
    {
        $this->startSession();
        return isset($_SESSION['userId']);
    }
    public function getCurrentUser(): ?User
    {
        $this->startSession();
        if($this->check()){
            $userId = $_SESSION['userId'];
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
                $this->startSession();
                $_SESSION['userId'] = $user->getId();
                return true;
            } else {
                return false;
            }
        }
    }
    public function logout()
    {
        $this->startSession();
        session_destroy();
    }
    private function startSession()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}