<?php
// Password Reset Script - Run this once then DELETE
echo "<h3>Password Reset Tool</h3>";

require_once 'includes/db.php';

$password = 'ishaque@321';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if admin table exists
    $stmt = $pdo->query("SELECT 1 FROM admin LIMIT 1");
    echo "✓ Admin table exists<br>";
} catch (Exception $e) {
    echo "✗ Admin table doesn't exist. Creating it...<br>";
    
    // Get the driver name
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    
    if ($driver === 'mysql') {
        $pdo->exec("CREATE TABLE IF NOT EXISTS admin (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    } else if ($driver === 'pgsql') {
        $pdo->exec("CREATE TABLE IF NOT EXISTS admin (
            id SERIAL PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    } else {
        die("Unsupported database driver: $driver");
    }
}

// Get the driver name
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

if ($driver === 'mysql') {
    $sql = "INSERT INTO admin (username, password) VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE password = ?";
    $params = ['admin', $hashed_password, $hashed_password];
} else if ($driver === 'pgsql') {
    $sql = "INSERT INTO admin (username, password) VALUES (?, ?) 
            ON CONFLICT (username) DO UPDATE SET password = EXCLUDED.password";
    $params = ['admin', $hashed_password];
} else {
    die("Unsupported database driver: $driver");
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo "✓ Password reset successfully!<br>";
echo "Username: <strong>admin</strong><br>";
echo "Password: <strong>ishaque@321</strong><br>";
echo "Hashed: " . $hashed_password . "<br><br>";

// Test the password
if (password_verify($password, $hashed_password)) {
    echo "✓ Password verification works!<br>";
} else {
    echo "✗ Password verification failed!<br>";
}

echo "<br><strong>IMPORTANT: Delete this file after use!</strong>";
?>