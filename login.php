<?php
session_start();
require "dbconfig.php";

if (isset($_POST['login'])) {
    $user_input = $_POST['user_input'];
    $pass = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND password = ?");
    $stmt->execute([$user_input, $user_input, $pass]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        // SUCCESSFUL REDIRECT MODIFIED HERE: Now goes to homepage.php
        echo '<script>alert("Logged in!"); window.location.href="homepage.php";</script>';
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sportify - Log In</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="auth-container">
        <img src="images/logo.png" alt="Sportify Logo" class="logo">
        <h1>Log in to your Sportify Account.</h1>
        
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST">
            <div class="form-group">
                <input type="text" name="user_input" placeholder="Username or Email*" required>
            </div>
            
            <div class="form-group">
                <input type="password" name="password" placeholder="Password*" required>
            </div>
            
            <button type="submit" name="login" class="btn-dark">Log in</button>
        </form>
        
        <div class="footer-links">
            <p><a href="forgot.php">Forgot Password?</a></p>
            <p>Don't have an account? <a href="signup1.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>