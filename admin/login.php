<?php
session_start();
require_once '../includes/db.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

// Check for logout success
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $success = "You have been successfully logged out!";
}

// Check for session timeout
if (isset($_GET['timeout']) && $_GET['timeout'] === 'true') {
    $error = "Your session has expired. Please login again.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin) {
                if (password_verify($password, $admin['password'])) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['login_time'] = time();
                    
                    header('Location: index.php');
                    exit;
                } else {
                    $error = "Invalid password!";
                }
            } else {
                $error = "User not found!";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please enter both username and password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" value="admin" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        
        <div style="margin-top: 20px; text-align: center;">
            <small>Default: admin / ishaque@321</small>
        </div>
    </div>
</body>
</html>