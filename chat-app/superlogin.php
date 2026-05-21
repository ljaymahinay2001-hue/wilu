<?php
session_start();
include 'db.php';

$message = "";

if ($_POST) {
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Invalid admin login";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<script>
history.pushState(null, null, location.href);
window.onpopstate = function () {
    history.go(1);
};
</script>

<div class="container">
    <h2>Admin Login</h2>
    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="POST">
        <input name="username" required placeholder="Admin Username">
        <input type="password" name="password" required placeholder="Password">
        <button>Login as Admin</button>
    </form>
    <p>No admin account? <a href="superregister.php">Register Admin</a></p>
</div>

</body>
</html>
