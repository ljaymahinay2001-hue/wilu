<?php
session_start();
include 'db.php';

$message = "";
$success = false;

if ($_POST) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        // Automatically set the role to 'admin' and approved to 1
        $stmt = $db->prepare("INSERT INTO users (username, password, role, approved) VALUES (?, ?, 'admin', 1)");
        $stmt->execute([$username, $password]);

        $message = "Admin registered successfully!";
        $success = true;
    } catch (PDOException $e) {
        $message = "Username already exists!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Admin Register</h2>
    <?php if ($message): ?>
        <p class="<?= $success ? 'success' : 'message' ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="POST">
        <input name="username" required placeholder="Admin Username">
        <input type="password" name="password" required placeholder="Password">
        <button>Register Admin</button>
    </form>
    <p><a href="superlogin.php">Back to Admin Login</a></p>
</div>

</body>
</html>
