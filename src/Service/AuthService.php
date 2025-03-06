<?php

namespace Service;

use Model\User;

class AuthService
{
    protected User $userModel;
    public function __construct()
    {
        $this->userModel = new User();
    }
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
            $user = $this->userModel->getById($userId);
            return $user;
        }
        else{
            return null;
        }
    }
    public function auth(string $email, string $password): bool
    {
        $user = $this->userModel->getByEmail($email);
        if (!$user) {
            return false;
        } else {
            $passwordDb = $user->getPassword();
            if (password_verify($password, $passwordDb)) {
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