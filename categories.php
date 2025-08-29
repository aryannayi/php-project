<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';
$categories = getAllCategories($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - TechStore</title>
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
                    <li><a href="categories.php" class="active">Categories</a></li>
                    <li><a href="about.php">About</a></li>
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

<section class="categories">
    <div class="container">
        <h2>Shop by Category</h2>
        <div class="categories-grid">
            <?php foreach ($categories as $category): ?>
            <div class="category-card">
                <div class="category-icon">
                    <i class="<?php echo $category['icon']; ?>"></i>
                </div>
                <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                <p><?php echo htmlspecialchars($category['description']); ?></p>
                <a href="products.php?category=<?php echo $category['id']; ?>" class="btn-secondary">Browse</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>
