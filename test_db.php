<?php
// Test database connection
echo "<h3>Database Connection Test</h3>";

try {
    require_once 'includes/db.php';
    echo "✓ Database connected successfully!<br>";
    
    // Test if we can query
    $stmt = $pdo->query("SELECT DATABASE() as db");
    $db = $stmt->fetch();
    echo "✓ Current database: " . $db['db'] . "<br>";
    
    // Check tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "✓ Tables found: " . implode(', ', $tables) . "<br>";
    
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "<br>";
    
    // Show connection details (remove in production)
    echo "Connection details used:<br>";
    echo "Host: localhost<br>";
    echo "Database: admin_dashboard<br>";
    echo "Username: root<br>";
}
?>