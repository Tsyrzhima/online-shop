<?php

namespace Controller;

use DTO\AuthenticationDTO;
use Model\User;
use Request\EditProfileRequest;
use Request\LoginRequest;
use Request\RegistrateRequest;

class UserController extends BaseController
{
    private User $userModel;
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }
    public function getRegistrate()
    {
        if($this->authService->check()) {
            header('Location: /catalog');
            exit();
        }else {
            require_once '../Views/registration_form.php';
        }
    }

    public function getLogin()
    {
        if ($this->authService->check()) {
            header('Location: /catalog');
            exit();
        } else {
            require_once '../Views/login_form.php';
        }
    }

    public function getEditProfile()
    {
       if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        } else {
           $user = $this->authService->getCurrentUser();
           require_once '../Views/edit_profile_form.php';
        }
    }

    public function getProfile()
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        } else {
            $this->profile();
        }
    }

    public function registrate(RegistrateRequest $request)
    {
        $errors = $request->validate();

        if (empty($errors)) {
            $hashed_password = password_hash($request->getPassword(), PASSWORD_DEFAULT);
            $this->userModel->registrate($request->getName(), $request->getEmail(), $hashed_password);
            header('Location: /catalog');
            exit();
        }
        require_once '../Views/registration_form.php';
    }

    public function login(LoginRequest $request)
    {
        $errors = $request->validate();

        if (empty($errors)) {
            $dto = new AuthenticationDTO($request->getEmail(), $request->getPassword());
            $result = $this->authService->auth($dto);
            if ($result) {
                header('Location: catalog');
                exit();
            } else {
                $errors['autorization'] = 'email или пароль неверный';
            }
        }
        require_once '../Views/login_form.php';
    }

    public function editProfile(EditProfileRequest $request)
    {
        $user = $this->authService->getCurrentUser();
        $userId = $user->getId();
        $errors = $request->validate($user);
        $flag = false;

        if (empty($errors)) {
            if (!empty($request->getName()) && ($user->getName() !== $request->getName())) {
                $this->userModel->updateNameById($request->getName(), $userId);
                $flag = true;
            }
            if (!empty($request->getEmail()) && ($user->getEmail() !== $request->getEmail())) {
                $this->userModel->updateEmailById($request->getEmail(), $userId);
                $flag = true;
            }
            if (!empty($request->getPassword()) && (!password_verify($request->getPassword(), $user->getPassword()))) {
                $hashed_password = password_hash($request->getPassword(), PASSWORD_DEFAULT);
                $this->userModel->updatePasswordById($hashed_password, $userId);
                $flag = true;
            }
            if ($flag) {
                header('Location: profile');
                exit();
            }
        }
        require_once '../Views/edit_profile_form.php';
    }
    public function profile()
    {
        if (!$this->authService->check()) {
            header('Location: login');
            exit();
        } else {
            $user = $this->authService->getCurrentUser();
            require_once '../Views/profile_form.php';
        }
    }
    public function logout()
    {
        $this->authService->logout();
        header('Location: /login');
        exit();
    }

}