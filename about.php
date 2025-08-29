<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - TechStore</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1><i class="fas fa-laptop"></i> TechStore</h1>
                </div>
                <nav class="nav">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="products.php">Products</a></li>
                        <li><a href="categories.php">Categories</a></li>
                        <li><a href="about.php" class="active">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </nav>
                <div class="header-actions">
                    <div class="user-actions">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="profile.php" class="btn-secondary"><i class="fas fa-user"></i> Profile</a>
                            <a href="logout.php" class="btn-secondary">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="btn-secondary">Login</a>
                            <a href="register.php" class="btn-primary">Register</a>
                        <?php endif; ?>
                        <a href="cart.php" class="cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo getCartItemCount(); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container" style="padding: 2rem 0;">
        <section class="about-section">
            <h1>About TechStore</h1>
            <p>Welcome to TechStore, your premier destination for cutting-edge electronics and technology products. Founded with a passion for innovation and quality, we've been serving tech enthusiasts and professionals since our establishment.</p>
            
            <h2>Our Mission</h2>
            <p>To provide our customers with the latest and most reliable technology products, backed by exceptional service and competitive pricing. We believe that everyone deserves access to quality technology that enhances their daily lives.</p>
            
            <h2>What We Offer</h2>
            <ul>
                <li><strong>Premium Electronics:</strong> From laptops and smartphones to gaming consoles and audio equipment</li>
                <li><strong>Expert Support:</strong> Our knowledgeable team is here to help you make informed decisions</li>
                <li><strong>Quality Assurance:</strong> All products are carefully selected and tested for reliability</li>
                <li><strong>Competitive Pricing:</strong> We offer the best value for your technology investments</li>
            </ul>
            
            <h2>Why Choose TechStore?</h2>
            <p>With years of experience in the technology retail industry, we understand what our customers need. Our commitment to quality, customer satisfaction, and staying ahead of technology trends makes us the preferred choice for tech enthusiasts and professionals alike.</p>
        </section>
    </main>

    <?php include 'partials/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>
