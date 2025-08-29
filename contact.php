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
    <title>Contact Us - TechStore</title>
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
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php" class="active">Contact</a></li>
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
        <section class="contact-section">
            <h1>Contact Us</h1>
            <p>Have questions or need assistance? We're here to help! Reach out to us through any of the following channels.</p>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin: 2rem 0;">
                <div class="contact-info">
                    <h2>Get in Touch</h2>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Address:</strong><br>
                            123 Tech Street, Digital City<br>
                            Tech District, TD 12345
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>Phone:</strong><br>
                            +1 (555) 123-4567
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Email:</strong><br>
                            info@techstore.com
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>Business Hours:</strong><br>
                            Monday - Friday: 9:00 AM - 6:00 PM<br>
                            Saturday: 10:00 AM - 4:00 PM<br>
                            Sunday: Closed
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <h2>Send us a Message</h2>
                    <form>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <?php include 'partials/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>
