<?php
session_start();
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

// Get product ID from URL
$id = $_GET['id'] ?? null;

// Validate product ID
if (!$id || !is_numeric($id)) {
    $_SESSION['error'] = "Invalid product ID!";
    header('Location: index.php');
    exit;
}

// Fetch product data
$product = getProductById($id);

// Check if product exists
if (!$product) {
    $_SESSION['error'] = "Product not found!";
    header('Location: index.php');
    exit;
}

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Validate form data
    if (empty($name)) {
        $error = "Product name is required!";
    } elseif (empty($price) || !is_numeric($price) || $price < 0) {
        $error = "Valid price is required!";
    } else {
        // Update product
        if (updateProduct($id, $name, $price, $description)) {
            $_SESSION['success'] = "Product updated successfully!";
            header('Location: index.php');
            exit;
        } else {
            $error = "Failed to update product. Please try again.";
        }
    }
}

// Check for session messages
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="dashboard-header">
            <h2>Edit Product</h2>
            <div>
                <a href="index.php" class="btn">Back to Dashboard</a>
            </div>
        </div>

        <!-- Success Message -->
        <?php if (!empty($success)): ?>
            <div class="success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>
            <div class="error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Edit Product Form -->
        <div class="form-container">
            <form method="POST" action="edit_product.php?id=<?php echo $id; ?>">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" 
                           value="<?php echo htmlspecialchars($product['name']); ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="price">Price ($):</label>
                    <input type="number" id="price" name="price" 
                           value="<?php echo htmlspecialchars($product['price']); ?>" 
                           step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" 
                              rows="4" 
                              placeholder="Enter product description..."><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Update Product</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

        <!-- Product Preview -->
        <div class="preview-section">
            <h3>Product Preview</h3>
            <div class="product-card preview">
                <h4 id="preview-name"><?php echo htmlspecialchars($product['name']); ?></h4>
                <div class="price" id="preview-price">$<?php echo number_format($product['price'], 2); ?></div>
                <div class="description" id="preview-description">
                    <?php echo htmlspecialchars($product['description'] ?: 'No description available.'); ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Real-time preview update
        document.getElementById('name').addEventListener('input', function() {
            document.getElementById('preview-name').textContent = this.value || 'Product Name';
        });

        document.getElementById('price').addEventListener('input', function() {
            const price = parseFloat(this.value) || 0;
            document.getElementById('preview-price').textContent = '$' + price.toFixed(2);
        });

        document.getElementById('description').addEventListener('input', function() {
            document.getElementById('preview-description').textContent = 
                this.value || 'No description available.';
        });
    </script>
</body>
</html>