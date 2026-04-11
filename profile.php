<?php
session_start();
require "dbconfig.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['save_profile'])) {
    $username = $_POST['username'];
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    
    $birthday = trim($_POST['month'] . " " . $_POST['day'] . ", " . $_POST['year']);

    try {
        // Check if username is taken
        $check = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $check->execute([$username, $user_id]);
        
        if ($check->fetch()) {
            echo '<script>alert("Username already taken!");</script>';
        } else {
            
            // --- IMAGE UPLOAD LOGIC ---
            $profile_image = null;
            
            // Check if user uploaded a file
            if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] == 0) {
                $target_dir = "uploads/";
                $file_extension = pathinfo($_FILES["profile_img"]["name"], PATHINFO_EXTENSION);
                
                // Name the image after the user's ID to prevent overwriting other files
                $new_filename = "user_" . $user_id . "." . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                // Check if it's an actual image
                $check_mime = getimagesize($_FILES["profile_img"]["tmp_name"]);
                if ($check_mime !== false) {
                    if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
                        $profile_image = $target_file;
                    }
                }
            }
            
            // If the user uploaded a new image, we update it. If not, we keep the old query.
            if ($profile_image) {
                $sql = "UPDATE users SET username=?, first_name=?, last_name=?, birthday=?, address=?, phone=?, gender=?, email=?, profile_image=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$username, $first, $last, $birthday, $address, $phone, $gender, $email, $profile_image, $user_id]);
            } else {
                $sql = "UPDATE users SET username=?, first_name=?, last_name=?, birthday=?, address=?, phone=?, gender=?, email=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$username, $first, $last, $birthday, $address, $phone, $gender, $email, $user_id]);
            }
            
            $_SESSION['username'] = $username;
            echo '<script>alert("Profile Updated Successfully!"); window.location.href="profile.php";</script>';
        }
    } catch(PDOException $e) {
        echo '<script>alert("Error: ' . addslashes($e->getMessage()) . '");</script>';
    }
}

// Fetch data for display
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Birthday Splitter Logic
$b_month = $b_day = $b_year = "";
if (!empty($user['birthday']) && strpos($user['birthday'], ',') !== false) {
    $parts = explode(" ", $user['birthday']);
    if (count($parts) >= 3) {
        $b_month = $parts[0];
        $b_day = rtrim($parts[1], ',');
        $b_year = $parts[2];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sportify - Your Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header>
        <div class="container nav-flex">
            <a href="homepage.php" class="logo">SPORTIFY</a>
            <div class="nav-icons">
                <a href="profile.php" style="color: black; margin-right: 15px;"><i class="fas fa-user"></i></a>
                <a href="cart.php" style="color: black; margin-right: 15px;"><i class="fas fa-shopping-cart"></i></a>
                <a href="logout.php" style="color: black;"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </header>

    <main class="profile-container">
        <h1>Your Profile</h1>
        
        <form method="POST" class="profile-form" enctype="multipart/form-data">
            
            <div class="avatar-box">
                <?php if (!empty($user['profile_image'])): ?>
                    <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" style="width:100%; height:100%; object-fit:cover; border-radius:10px;">
                <?php endif; ?>
            </div>

            <div class="form-group" style="text-align: center; margin-top: 10px;">
                <label style="font-size: 14px; font-weight: bold;">Change Profile Picture</label><br>
                <input type="file" name="profile_img" style="border: none; padding: 10px 0;">
            </div>

            <div class="form-group">
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" placeholder="Username*" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" placeholder="First Name*" required>
                </div>
                <div class="form-group">
                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" placeholder="Last Name*" required>
                </div>
            </div>

            <div class="form-row three-cols">
                <div class="form-group">
                    <input type="text" name="month" value="<?php echo htmlspecialchars($b_month); ?>" placeholder="Month*">
                </div>
                <div class="form-group">
                    <input type="text" name="day" value="<?php echo htmlspecialchars($b_day); ?>" placeholder="Day*">
                </div>
                <div class="form-group">
                    <input type="text" name="year" value="<?php echo htmlspecialchars($b_year); ?>" placeholder="Year*">
                </div>
            </div>

            <div class="form-group">
                <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" placeholder="Address*">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="Phone No.*" required>
                </div>
                <div class="form-group">
                    <input type="text" name="gender" value="<?php echo htmlspecialchars($user['gender'] ?? ''); ?>" placeholder="Gender*">
                </div>
            </div>

            <div class="form-group">
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email*" required>
            </div>

            <button type="submit" name="save_profile" class="btn-save">Save</button>
        </form>
    </main>

            <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a href="#" class="logo">SPORTIFY</a>
                    <p>We have clothes that suit your style and which you're proud to wear. From women to men.</p>
                </div>
                <div class="footer-col">
                    <h4>Company</h4>
                    <ul><li><a href="#">About</a></li><li><a href="#">Features</a></li><li><a href="#">Works</a></li><li><a href="#">Career</a></li></ul>
                </div>
                <div class="footer-col">
                    <h4>Help</h4>
                    <ul><li><a href="#">Customer Support</a></li><li><a href="#">Delivery Details</a></li><li><a href="#">Terms & Conditions</a></li><li><a href="#">Privacy Policy</a></li></ul>
                </div>
                <div class="footer-col">
                    <h4>FAQ</h4>
                    <ul><li><a href="#">Account</a></li><li><a href="#">Manage Deliveries</a></li><li><a href="#">Orders</a></li><li><a href="#">Payments</a></li></ul>
                </div>
                <div class="footer-col">
                    <h4>Resources</h4>
                    <ul><li><a href="#">Free eBooks</a></li><li><a href="#">Development Tutorial</a></li><li><a href="#">How to - Blog</a></li><li><a href="#">Youtube Playlist</a></li></ul>
                </div>
            </div>
            <hr class="footer-divider">
            <p class="copyright">Sportify © 2000-2026, All Rights Reserved</p>
        </div>
    </footer>

</body>
</html>