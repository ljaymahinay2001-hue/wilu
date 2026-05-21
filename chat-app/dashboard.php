<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];
?>
<script>
history.pushState(null, null, location.href);
window.onpopstate = function () {
    history.go(1);
};
</script>


<h2>Welcome <?= htmlspecialchars($user['username']) ?></h2>

<a href="chat.php">💬 Enter Chat</a><br>

<?php if ($user['role'] == 'admin'): ?>
    <a href="admin.php">⚙️ Admin Panel</a><br>
<?php endif; ?>

<a href="logout.php">🚪 Logout</a>