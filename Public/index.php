<?php
session_start();

// tapos itong buong if block, ikaw na bahala kung saan mo iredirect kapag minanual lipat yung url sa index.php mareredirect siya
// sa otp-verify or login 
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["otp_verified"]) || $_SESSION["otp_verified"] !== true) {
    header("Location: otp-verify.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Welcome to the App</h1>
    <?php if (isset($_SESSION["user_id"])): ?>
        <p>Hi <?= htmlspecialchars($_SESSION["user_name"] ?? 'User') ?>, You are logged in.</p>
        <a href="logout.php">Log out</a>
    <?php else: ?>
        <p>You are not logged in.</p>
        <a href="login.php">Log in</a> | <a href="signup.php">Sign up</a>
    <?php endif; ?>
</body>
</html>
