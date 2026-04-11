<?php
session_start();

// Check if user is logged in. If not, bounce them back to login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPORTIFY - Premium Style Collection</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header>
        <div class="container nav-flex">
            <a href="#" class="logo">SPORTIFY</a>
            <div class="nav-icons">
    <span style="margin-right: 15px; font-weight: 500;">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
    
    <a href="profile.php" style="color: black; margin-right: 20px;"><i class="fas fa-user"></i></a>
    
    <a href="cart.php" style="color: black; margin-right: 20px;"><i class="fas fa-shopping-cart"></i></a>
    
    <a href="logout.php" style="color: black;"><i class="fas fa-sign-out-alt"></i></a>
</div>
        </div>
    </header>

    <main class="container">
        <section class="hero">
            <div class="hero-grid">
                <div class="hero-left">
                    <h1>Find Clothes That Matches Your Style</h1>
                    <p>Browse through our diverse range of meticulously crafted garments, designed to bring out your individuality and cater to your sense of style.</p>
                    <a href="#" class="btn-black">Shop Now</a>
                    
                    <div class="hero-stats">
                        <div class="stat-item"><h3>200+</h3><p>International Brands</p></div>
                        <div class="stat-item"><h3>2,000+</h3><p>High-Quality Products</p></div>
                        <div class="stat-item"><h3>30,000+</h3><p>Happy Customers</p></div>
                    </div>
                </div>
                
            </div>
        </section>

        <section class="gender-selector">
            <a href="#" class="gender-btn">Men</a>
            <a href="#" class="gender-btn">Women</a>
        </section>

        <h2 class="section-title">New Arrivals</h2>
<div class="product-grid">
    <?php 
   
    $new_arrivals = [
        ["name" => "adidas Men's Barricade 14 Tennis Shoes", "price" => "₱9,500.00", "img" => "image/shoe1.webp"],
        ["name" => "adidas Unisex Harden Volume 10 Basketball Shoes", "price" => "₱9,500.00", "img" => "image/shoe2.webp"],
        ["name" => "Nike Men's Tatum 4 PF Basketball Shoes", "price" => "₱6,565.50", "img" => "image/shoe3.webp"],
        ["name" => "Nike Men's Giannis Immortality 4 EP Basketball Shoes", "price" => "₱3,865.50", "img" => "image/shoe4.webp"]
    ];


    foreach($new_arrivals as $product): ?>
    <div class="product-card">
        <div class="img-holder">
            <img src="<?php echo $product['img']; ?>" alt="<?php echo $product['name']; ?>">
        </div>
        <h4><?php echo $product['name']; ?></h4>
        <p class="price"><?php echo $product['price']; ?></p>
    </div>
    <?php endforeach; ?>
</div>

        <h2 class="section-title">Top Selling</h2>
<div class="product-grid">
    <?php 
   
    $top_selling = [
        ["name" => "Nike Men's Victori One Slides", "price" => "₱1,435.50", "img" => "image/tsinelas1.webp"],
        ["name" => "Nike Men's ReactX Rejuven8 Slides", "price" => "₱2,965.50", "img" => "image/tsinelas2.webp"],
        ["name" => "Nadidas Men's Pro Block Shorts", "price" => "₱900.00 ", "img" => "image/short1.webp"],
        ["name" => "ANTA Men's Cross-Training SS Tee Shirt Slim Fit", "price" => "₱1,197.00", "img" => "image/damit1.webp"]
    ];

    foreach($top_selling as $product): ?>
    <div class="product-card">
        <div class="img-holder">
            <img src="<?php echo $product['img']; ?>" alt="<?php echo $product['name']; ?>">
        </div>
        <h4><?php echo $product['name']; ?></h4>
        <div class="rating">
        </div>
        <p class="price"><?php echo $product['price']; ?></p>
    </div>
    <?php endforeach; ?>
</div>

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