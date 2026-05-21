<?php
session_start();
include 'db.php';

$message = "";

if ($_POST) {
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($_POST['password'], $user['password'])) {

        if ($user['approved'] == 0 && $user['role'] != 'admin') {
            $message = "Waiting for admin approval.";
        } else {
            $_SESSION['user'] = $user;
            header("Location: dashboard.php");
            exit;
        }
    } else {
        $message = "Invalid login";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    <h2>Login</h2>
    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="POST">
        <input name="username" required placeholder="Username">
        <input type="password" name="password" required placeholder="Password">
        <button>Login</button>
    </form>
    <p>No account? <a href="register.php">Register</a></p>
</div>

</body>
</html>