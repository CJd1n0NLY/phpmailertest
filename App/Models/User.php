<?php

namespace App\Models;

use mysqli;

class User {
    private $mysqli;

    public function __construct(mysqli $mysqli) {
        $this->mysqli = $mysqli;
    }

    public function create($name, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $activation_hash = bin2hex(random_bytes(16)); // More secure activation hash

        $sql = "INSERT INTO user (name, email, password_hash, account_activation_hash) VALUES (?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $activation_hash);
        
        if ($stmt->execute()) {
            // Send activation email
            $this->sendActivationEmail($email, $activation_hash);
            return true; // Indicate successful registration
        }

        return false; // Indicate failure
    }

    private function sendActivationEmail($email, $activation_hash) {
        // Note: We are using the raw activation hash in the URL
        $activationLink = "http://localhost/phpmailertest/Public/activate-account.php?token=" . $activation_hash;
        
        // Load the mailer and send the email
        $mail = require __DIR__ . '/../../Public/mailer.php'; // Adjust the path as needed
        $mail->setFrom('noreply@yourdomain.com', 'Your App');
        $mail->addAddress($email);
        $mail->Subject = 'Activate Your Account';
        $mail->Body = "Please click the following link to activate your account: <a href='$activationLink'>$activationLink</a>";
        $mail->isHTML(true);

        // Attempt to send the email
        $mail->send();
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function findByActivationToken($token_hash) {
        $sql = "SELECT * FROM user WHERE account_activation_hash = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $token_hash);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function activateAccount($userId) {
        $sql = "UPDATE user SET account_activation_hash = NULL WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $userId);
        return $stmt->execute();
    }

    public function verifyPassword($user, $password) {
        return password_verify($password, $user['password_hash']);
    }
}

