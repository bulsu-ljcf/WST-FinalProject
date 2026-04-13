<?php
require "dbconfig.php";

if (isset($_POST['reset'])) {
    $username = $_POST['username'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    if ($new_pass !== $confirm_pass) {
        $error = "New passwords do not match!";
    } else {
        $pass_hash = md5($new_pass);
        
        // Check if user exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->fetch()) {
            $update = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $update->execute([$pass_hash, $username]);
            echo '<script>alert("Password updated!"); window.location.href="login.php";</script>';
        } else {
            $error = "Username not found!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sportify - Reset Password</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="auth-container">
        <img src="images/logo.png" alt="Sportify Logo" class="logo">
        <h1>Reset your Sportify Passwords.</h1>
        
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username*" required>
            </div>
            
            <div class="form-group">
                <input type="password" name="new_pass" placeholder="New Password*" required>
            </div>

            <div class="form-group">
                <input type="password" name="confirm_pass" placeholder="Re-enter Password*" required>
            </div>
            
            <button type="submit" name="reset" class="btn-dark">Confirm</button>
        </form>
        
        <div class="footer-links">
            <p><a href="login.php">Back to Log In</a></p>
        </div>
    </div>
</body>
</html>