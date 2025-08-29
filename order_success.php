<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if (!$order_id) {
    redirect('index.php');
}

$user_id = $_SESSION['user_id'];
$order_details = getOrderDetails($conn, $order_id, $user_id);

if (empty($order_details)) {
    redirect('index.php');
}

$order = $order_details[0]; // First item contains order info
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - TechStore</title>
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
                    </ul>
                </nav>
                <div class="header-actions">
                    <div class="user-actions">
                        <a href="profile.php" class="btn-secondary"><i class="fas fa-user"></i> Profile</a>
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

    <main class="container" style="padding: 2rem 0;">
        <div class="success-container" style="text-align: center; max-width: 600px; margin: 0 auto;">
            <div class="success-icon" style="font-size: 4rem; color: #22c55e; margin-bottom: 1rem;">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1>Order Placed Successfully!</h1>
            <p>Thank you for your purchase. Your order has been confirmed and will be processed soon.</p>
            
            <div class="order-details" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin: 2rem 0; text-align: left;">
                <h2>Order Details</h2>
                <p><strong>Order ID:</strong> #<?php echo $order_id; ?></p>
                <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                <p><strong>Total Amount:</strong> <?php echo formatPrice($order['total_amount']); ?></p>
                <p><strong>Status:</strong> <span style="color: #f59e0b;"><?php echo ucfirst($order['status']); ?></span></p>
            </div>
            
            <div class="order-items" style="margin: 2rem 0;">
                <h3>Items Ordered</h3>
                <?php foreach ($order_details as $item): ?>
                <div class="order-item" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border-bottom: 1px solid #eee;">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;" onerror="this.onerror=null;this.src='https://via.placeholder.com/60x60?text=Product';">
                    <div>
                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                        <p>Quantity: <?php echo (int)$item['quantity']; ?> × <?php echo formatPrice($item['price']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="actions" style="margin: 2rem 0;">
                <a href="products.php" class="btn-primary">Continue Shopping</a>
                <a href="profile.php" class="btn-secondary">View Profile</a>
            </div>
            
            <p style="color: #666; font-size: 0.9rem;">
                You will receive an email confirmation shortly. If you have any questions, please contact our support team.
            </p>
        </div>
    </main>

    <?php include 'partials/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>
