<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    redirect('login.php?redirect=cart.php');
}

$user_id = $_SESSION['user_id'];
$cart_items = getCartItems($conn, $user_id);
$total = getCartTotal($conn, $user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - TechStore</title>
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
                    <a href="logout.php" class="btn-secondary">Logout</a>
                    <a href="cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count"><?php echo getCartItemCount(); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="container">
    <div class="cart-container">
        <h2>Your Shopping Cart</h2>
        <?php if (empty($cart_items)): ?>
            <p>Your cart is empty.</p>
            <p><a href="products.php" class="btn-primary">Continue Shopping</a></p>
        <?php else: ?>
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <img class="cart-item-image" src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div class="cart-item-details">
                        <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                        <div class="cart-item-price"><?php echo formatPrice($item['price']); ?></div>
                    </div>
                    <div class="cart-item-quantity">
                        <button class="quantity-btn" data-action="decrease" data-cart-id="<?php echo $item['id']; ?>">-</button>
                        <span class="quantity-display"><?php echo (int)$item['quantity']; ?></span>
                        <button class="quantity-btn" data-action="increase" data-cart-id="<?php echo $item['id']; ?>">+</button>
                    </div>
                    <button class="btn-secondary" onclick="removeFromCart(<?php echo $item['id']; ?>)">Remove</button>
                </div>
            <?php endforeach; ?>
            <div class="cart-total">
                <h3>Total: <span class="total-amount"><?php echo formatPrice($total); ?></span></h3>
                <button class="btn-primary" onclick="proceedToCheckout()">Proceed to Checkout</button>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="assets/js/main.js"></script>
<script>
    calculateCartTotal();
</script>
</body>
</html>
