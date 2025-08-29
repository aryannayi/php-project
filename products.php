<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Filters
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';
$min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build SQL
$sql = "SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE 1=1";
$params = [];
$types = '';

if ($category_id) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category_id;
    $types .= 'i';
}

if ($search !== '') {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $like = "%$search%";
    $params[] = $like; $params[] = $like;
    $types .= 'ss';
}

if ($min_price !== null) {
    $sql .= " AND p.price >= ?";
    $params[] = $min_price;
    $types .= 'd';
}

if ($max_price !== null) {
    $sql .= " AND p.price <= ?";
    $params[] = $max_price;
    $types .= 'd';
}

switch ($sort) {
    case 'price_asc':
        $sql .= " ORDER BY p.price ASC"; break;
    case 'price_desc':
        $sql .= " ORDER BY p.price DESC"; break;
    case 'name_asc':
        $sql .= " ORDER BY p.name ASC"; break;
    default:
        $sql .= " ORDER BY p.created_at DESC"; // newest
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$categories = getAllCategories($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - TechStore</title>
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
                    <li><a href="products.php" class="active">Products</a></li>
                    <li><a href="categories.php">Categories</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
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

<section class="featured-products" style="padding-top:2rem;">
    <div class="container">
        <h2>Browse Products</h2>

        <div style="display:flex; gap:1rem; align-items:center; margin:1rem 0; flex-wrap:wrap;">
            <select onchange="filterProducts(this.value)">
                <option value="all">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo ($category_id == $cat['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                <?php endforeach; ?>
            </select>

            <select onchange="sortProducts(this.value)">
                <option value="newest" <?php echo $sort==='newest'?'selected':''; ?>>Newest</option>
                <option value="price_asc" <?php echo $sort==='price_asc'?'selected':''; ?>>Price: Low to High</option>
                <option value="price_desc" <?php echo $sort==='price_desc'?'selected':''; ?>>Price: High to Low</option>
                <option value="name_asc" <?php echo $sort==='name_asc'?'selected':''; ?>>Name: A to Z</option>
            </select>

            <div>
                <input type="number" id="minPrice" placeholder="Min" step="0.01" value="<?php echo $min_price!==null?htmlspecialchars($min_price):''; ?>" style="width:100px;"> -
                <input type="number" id="maxPrice" placeholder="Max" step="0.01" value="<?php echo $max_price!==null?htmlspecialchars($max_price):''; ?>" style="width:100px;">
                <button class="btn-secondary" onclick="filterByPrice(document.getElementById('minPrice').value, document.getElementById('maxPrice').value)">Apply</button>
            </div>
        </div>

        <div class="products-grid">
            <?php if (count($products) === 0): ?>
                <p>No products found.</p>
            <?php endif; ?>

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
    </div>
</section>

<?php include 'partials/footer.php'; // Optional if footer extracted ?>
<script src="assets/js/main.js"></script>
</body>
</html>
