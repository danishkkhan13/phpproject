<?php
require_once 'db.php';

// Function to add a product
function addProduct($name, $price, $description) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $price, $description]);
    } catch (PDOException $e) {
        error_log("Error adding product: " . $e->getMessage());
        return false;
    }
}

// Function to get all products
function getProducts() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting products: " . $e->getMessage());
        return [];
    }
}

// Function to get product by ID
function getProductById($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting product by ID: " . $e->getMessage());
        return false;
    }
}

// Function to update product details
function updateProduct($id, $name, $price, $description) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
        return $stmt->execute([$name, $price, $description, $id]);
    } catch (PDOException $e) {
        error_log("Error updating product: " . $e->getMessage());
        return false;
    }
}

// Function to delete a product
function deleteProduct($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("Error deleting product: " . $e->getMessage());
        return false;
    }
}

// Function to verify admin credentials
function verifyAdmin($username, $password) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Error verifying admin: " . $e->getMessage());
        return false;
    }
}
?>