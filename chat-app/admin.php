<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    exit("Access denied");
}

// approve
if (isset($_GET['approve'])) {
    $stmt = $db->prepare("UPDATE users SET approved = 1 WHERE id = ?");
    $stmt->execute([$_GET['approve']]);
}

$users = $db->query("SELECT * FROM users WHERE approved = 0 AND role='user'");
?>

<h2>Pending Users</h2>

<?php while ($row = $users->fetch(PDO::FETCH_ASSOC)): ?>
    <p>
        <?= htmlspecialchars($row['username']) ?>
        <a href="?approve=<?= $row['id'] ?>">Approve</a>
    </p>
<?php endwhile; ?>

<script>
history.pushState(null, null, location.href);
window.onpopstate = function () {
    history.go(1);
};
</script>

<a href="dashboard.php">⬅ Back</a>