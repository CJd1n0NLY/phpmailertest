<?php

use App\Core\Database;
use App\Models\User;
use App\Controllers\UserController;

require_once __DIR__ . '/../vendor/autoload.php';

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);
$userController = new UserController($userModel);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $activation_hash = $userController->register($_POST["name"], $_POST["email"], $_POST["password"]);

    // Send activation email with the link
    $activation_link = "http://localhost/activate-account.php?token=" . $activation_hash; // Update your domain
    // Use PHPMailer to send the activation link

    header("Location: signup-success.php");
    exit;
}
?>
