<?php
session_start();
include 'db.php';

$message = "";
$success = false;

if ($_POST) {

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);

        $message = "Registered! Wait for admin approval.";
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
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Register</h2>
    <?php if ($message): ?>
        <p class="<?= $success ? 'success' : 'message' ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="POST">
        <input name="username" required placeholder="Username">
        <input type="password" name="password" required placeholder="Password">
        <button>Register</button>
    </form>
    <p><a href="index.php">Back to Login</a></p>
</div>

</body>
</html>