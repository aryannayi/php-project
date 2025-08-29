<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = $product_id ? getProduct($conn, $product_id) : null;
if (!$product) {
    header('HTTP/1.0 404 Not Found');
}
$categories = getAllCategories($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product? htmlspecialchars($product['name']).' - ':''; ?>TechStore</title>
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

<section class="featured-products" style="padding-top:2rem;">
    <div class="container">
        <?php if (!$product): ?>
            <h2>Product not found</h2>
            <p><a href="products.php" class="btn-secondary">Back to Products</a></p>
        <?php else: ?>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:2rem; align-items:start;">
            <div>
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width:100%; border-radius:12px;" onerror="this.onerror=null;this.src='https://via.placeholder.com/800x600?text=Product+Image';">
            </div>
            <div>
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p class="product-category" style="margin-bottom:1rem;">Category: <?php echo htmlspecialchars($product['category_name']); ?></p>
                <div class="product-price" style="margin-bottom:1rem;">
                    <span class="current-price"><?php echo formatPrice($product['price']); ?></span>
                    <?php if (!empty($product['old_price'])): ?>
                        <span class="old-price"><?php echo formatPrice($product['old_price']); ?></span>
                    <?php endif; ?>
                </div>
                <p style="margin-bottom:1.5rem; color:#555;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <button class="btn-primary add-to-cart" data-product-id="<?php echo $product['id']; ?>">Add to Cart</button>
                <p style="margin-top:1rem; color:#666;">Stock: <?php echo (int)$product['stock']; ?></p>
            </div>
        </div>
        <?php 
            // Related products from same category
            if ($product) {
                $relatedStmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.id <> ? ORDER BY p.created_at DESC LIMIT 6");
                $relatedStmt->bind_param("ii", $product['category_id'], $product['id']);
                $relatedStmt->execute();
                $related = $relatedStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                if (count($related) > 0):
        ?>
        <div style="margin-top:3rem;">
            <h3>Related Electronics</h3>
            <div class="products-grid">
                <?php foreach ($related as $r): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://store.storeimages.cdn-apple.com/1/as-images.apple.com/is/mbp14-spaceblack-gallery1-202410?wid=4000&hei=3074&fmt=jpeg&qlt=90&.v=YnlWZDdpMFo0bUpJZnBpZjhKM2M3WGpXSTNqQ2U1MjQxSHBKRkRoWUE0bmd1eUJ6eHZMSFFNMld6aTRncXNRUlJWYlIvRkkxemNIb09FY29ZRmVrUDhQTUF6eWYycDMyY0I5TEVkZkpSbDU4aHA0S1QvclFGZSsvUzZRWUI0U1M" alt="<?php echo htmlspecialchars($r['name']); ?>" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=Product';">
                        <div class="product-overlay">
                            <a href="product.php?id=<?php echo $r['id']; ?>" class="btn-primary">View Details</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($r['name']); ?></h3>
                        <p class="product-category"><?php echo htmlspecialchars($r['category_name']); ?></p>
                        <div class="product-price">
                            <span class="current-price"><?php echo formatPrice($r['price']); ?></span>
                            <?php if (!empty($r['old_price'])): ?>
                                <span class="old-price"><?php echo formatPrice($r['old_price']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php 
                endif; 
            }
        ?>
        <?php endif; ?>
    </div>
</section>

<script src="assets/js/main.js"></script>
</body>
</html>
