<?php

namespace App\Controllers;

use App\Models\User;

class UserController {
    private $userModel;

    public function __construct(User $userModel) {
        $this->userModel = $userModel;
    }

    public function register($name, $email, $password) {
        // Create user in the database
        $user = $this->userModel->create($name, $email, $password);

        if ($user) {
            return ['success' => true, 'message' => 'Registration successful!'];
        }

        return ['success' => false, 'message' => 'User registration failed.'];
    }

    public function login($email, $password) {
        $user = $this->userModel->findByEmail($email);
    
        if ($user && $user['account_activation_hash'] === NULL && $this->userModel->verifyPassword($user, $password)) {
            return $user; 
        }
    
        return null; 
    }

    public function activate($token_hash) {
        return $this->userModel->findByActivationToken($token_hash);
    }

    public function confirmAccount($userId) {
        return $this->userModel->activateAccount($userId);
    }
}
