<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

$search_term = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';
$products = [];
$categories = getAllCategories($conn);

if (!empty($search_term)) {
    $products = searchProducts($conn, $search_term, 50);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - TechStore</title>
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
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </nav>
                <div class="header-actions">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search products..." value="<?php echo htmlspecialchars($search_term); ?>">
                        <button onclick="performSearch(document.getElementById('searchInput').value)"><i class="fas fa-search"></i></button>
                    </div>
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
        <section class="search-results">
            <h1>Search Results</h1>
            
            <?php if (!empty($search_term)): ?>
                <p>Searching for: "<strong><?php echo htmlspecialchars($search_term); ?></strong>"</p>
                <p>Found <?php echo count($products); ?> product(s)</p>
            <?php endif; ?>

            <?php if (empty($search_term)): ?>
                <div class="search-prompt">
                    <h2>Search Products</h2>
                    <p>Enter a search term above to find products.</p>
                </div>
            <?php elseif (empty($products)): ?>
                <div class="no-results">
                    <h2>No products found</h2>
                    <p>Try adjusting your search terms or browse our categories:</p>
                    <div class="categories-grid" style="margin-top: 1rem;">
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
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=Product';">
                            <div class="product-overlay">
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="btn-primary">View Details</a>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></p>
                            <div class="product-price">
                                <span class="current-price"><?php echo formatPrice($product['price']); ?></span>
                                <?php if (!empty($product['old_price'])): ?>
                                    <span class="old-price"><?php echo formatPrice($product['old_price']); ?></span>
                                <?php endif; ?>
                            </div>
                            <button class="btn-primary add-to-cart" data-product-id="<?php echo $product['id']; ?>">Add to Cart</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'partials/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>
