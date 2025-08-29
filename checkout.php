<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    redirect('login.php?redirect=checkout.php');
}

$user_id = $_SESSION['user_id'];
$cart_items = getCartItems($conn, $user_id);
$total = getCartTotal($conn, $user_id);

if (empty($cart_items)) {
    redirect('cart.php');
}

$profile = getUserProfile($conn, $user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TechStore</title>
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
                    <li><a href="cart.php">Cart</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <div class="user-actions">
                    <a href="logout.php" class="btn-secondary">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="container" style="display:grid; grid-template-columns: 2fr 1fr; gap:2rem; padding:2rem 0;">
    <div class="form-container">
        <h2>Shipping Information</h2>
        <form method="POST" action="place_order.php">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input id="first_name" name="first_name" value="<?php echo htmlspecialchars($profile['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input id="last_name" name="last_name" value="<?php echo htmlspecialchars($profile['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" required><?php echo htmlspecialchars($profile['address']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input id="phone" name="phone" value="<?php echo htmlspecialchars($profile['phone']); ?>" required>
            </div>
            <button type="submit" class="btn-primary form-submit">Place Order</button>
        </form>
    </div>

    <div class="cart-container">
        <h2>Order Summary</h2>
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-item" style="border-bottom:1px solid #eee; padding:0.5rem 0;">
                <img class="cart-item-image" src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                <div class="cart-item-details">
                    <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                    <div class="cart-item-price"><?php echo formatPrice($item['price']); ?> x <?php echo (int)$item['quantity']; ?></div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="cart-total">
            <h3>Total: <span class="total-amount"><?php echo formatPrice($total); ?></span></h3>
        </div>
    </div>
</main>

<script src="assets/js/main.js"></script>
</body>
</html>
