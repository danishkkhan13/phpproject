<?php
$host = 'localhost'; // Database host
$dbname = 'admin_dashboard'; // Database name
$username = 'postgres'; // Database username
$password = 'Danish@321'; // Database password
$port = '5433'; // PostgreSQL port

try {
    // Establish a connection to PostgreSQL using the specified port
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception mode for error handling
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
