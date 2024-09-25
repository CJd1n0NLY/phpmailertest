<?php
session_start();

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($_POST["otp"] == $_SESSION["otp"]) {
        $_SESSION["otp_verified"] = true; // ito dagdag mo kael 
        header("Location: index.php");
        exit;
    } else {
        $is_invalid = true;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>

<h1>Verify OTP</h1>

<?php if ($is_invalid): ?>
    <em>Invalid OTP. Please try again.</em>
<?php endif ?>

<form method="post">
    <label for="otp">Enter OTP</label>
    <input type="text" id="otp" name="otp" required>
    <button>Verify</button>
</form>

</body>
</html>
