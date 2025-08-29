<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    redirect('login.php?redirect=place_order.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('checkout.php');
}

$user_id = $_SESSION['user_id'];
$cart_items = getCartItems($conn, $user_id);
$total = getCartTotal($conn, $user_id);

if (empty($cart_items)) {
    redirect('cart.php');
}

// Get form data
$first_name = sanitizeInput($_POST['first_name']);
$last_name = sanitizeInput($_POST['last_name']);
$address = sanitizeInput($_POST['address']);
$phone = sanitizeInput($_POST['phone']);

// Update user profile with shipping info
updateUserProfile($conn, $user_id, $first_name, $last_name, $address, $phone);

// Create order
$shipping_address = "$first_name $last_name\n$address\nPhone: $phone";
$order_id = createOrder($conn, $user_id, $total, $shipping_address);

if ($order_id) {
    // Add order items
    addOrderItems($conn, $order_id, $cart_items);
    
    // Clear cart
    clearCart($conn, $user_id);
    
    // Redirect to success page
    redirect("order_success.php?order_id=$order_id");
} else {
    setFlashMessage('error', 'Failed to create order. Please try again.');
    redirect('checkout.php');
}
?>
