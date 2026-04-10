<?php
session_start();
require "dbconfig.php";

$email = $_SESSION['temp_email'] ?? '';

if (isset($_POST['register'])) {
    $first = $_POST['first'];
    $last = $_POST['last'];
    $username = $_POST['username'];
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];
    $birthday = $_POST['month'] . " " . $_POST['day'] . ", " . $_POST['year'];
    $phone = $_POST['phone'];

    if ($pass !== $cpass) {
        $error = "Passwords do not match!";
    } else {
        // MD5 is used here matching your original files, but password_hash() is recommended for production.
        $pass_hash = md5($pass); 
        
        try {
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password, birthday, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$first, $last, $username, $email, $pass_hash, $birthday, $phone]);
            
            unset($_SESSION['temp_email']);
            echo '<script>alert("Account Created!"); window.location.href="login.php";</script>';
        } catch(PDOException $e) {
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sportify - Register</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="auth-container">
        <img src="images/logo.png" alt="Sportify Logo" class="logo">
        <h1>Now let's make you a Sportify Member.</h1>
        
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST">
            <div class="form-group row">
                <div class="col"><input type="text" name="first" placeholder="First Name*" required></div>
                <div class="col"><input type="text" name="last" placeholder="Last Name*" required></div>
            </div>
            
            <div class="form-group">
                <input type="text" name="username" placeholder="Username*" required>
            </div>
            
            <div class="form-group">
                <input type="password" name="password" placeholder="Password*" required>
                <span class="instructions">Minimum of 8 characters. Uppercase, lowercase letters, and one number.</span>
            </div>
            
            <div class="form-group">
                <input type="password" name="cpassword" placeholder="Confirm Password*" required>
            </div>

            <div class="form-group row">
                <div class="col"><input type="text" name="month" placeholder="Month*" required></div>
                <div class="col"><input type="text" name="day" placeholder="Day*" required></div>
                <div class="col"><input type="text" name="year" placeholder="Year*" required></div>
            </div>

            <div class="form-group">
                <input type="tel" name="phone" placeholder="Phone No.*" required>
            </div>

            <div class="form-group">
                <label class="checkbox-container">
                    <input type="checkbox" required>
                    <span class="legal-text" style="margin:0;">I agree to Sportify's <a href="#">Privacy Policy</a> and <a href="#">Terms of Use</a>.</span>
                </label>
            </div>
            
            <button type="submit" name="register" class="btn-dark">Create Account</button>
        </form>
    </div>
</body>
</html>