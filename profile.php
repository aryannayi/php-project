<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    redirect('login.php?redirect=profile.php');
}

$user_id = $_SESSION['user_id'];
$flash = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = sanitizeInput($_POST['first_name']);
    $last = sanitizeInput($_POST['last_name']);
    $address = sanitizeInput($_POST['address']);
    $phone = sanitizeInput($_POST['phone']);
    if (updateUserProfile($conn, $user_id, $first, $last, $address, $phone)) {
        $flash = ['type' => 'success', 'message' => 'Profile updated'];
    } else {
        $flash = ['type' => 'error', 'message' => 'Failed to update profile'];
    }
}

$profile = getUserProfile($conn, $user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - TechStore</title>
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
    <div class="form-container">
        <h2>Your Profile</h2>
        <?php if ($flash): ?>
            <div class="flash-message <?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['message']); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" value="<?php echo htmlspecialchars($profile['username']); ?>" disabled>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" value="<?php echo htmlspecialchars($profile['email']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input id="first_name" name="first_name" value="<?php echo htmlspecialchars($profile['first_name']); ?>">
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input id="last_name" name="last_name" value="<?php echo htmlspecialchars($profile['last_name']); ?>">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address"><?php echo htmlspecialchars($profile['address']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input id="phone" name="phone" value="<?php echo htmlspecialchars($profile['phone']); ?>">
            </div>
            <button class="btn-primary form-submit" type="submit">Save Changes</button>
        </form>
    </div>
</main>

<?php include 'partials/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>
