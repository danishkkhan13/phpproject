<?php
session_start();
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($name && $price && is_numeric($price)) {
        if (addProduct($name, $price, $description)) {
            $success = "Product added successfully!";
            // Clear form
            $name = $price = $description = '';
        } else {
            $error = "Failed to add product. Please try again.";
        }
    } else {
        $error = "Please fill in all fields correctly!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="dashboard-header">
            <h2>Add New Product</h2>
            <a href="index.php" class="btn">Back to Dashboard</a>
        </div>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="add_product.php">
            <input type="text" name="name" placeholder="Product Name" value="<?= htmlspecialchars($name ?? '') ?>" required>
            <input type="number" name="price" placeholder="Price" step="0.01" min="0" value="<?= htmlspecialchars($price ?? '') ?>" required>
            <textarea name="description" placeholder="Product Description"><?= htmlspecialchars($description ?? '') ?></textarea>
            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>