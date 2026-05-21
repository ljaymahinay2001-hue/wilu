<?php
include 'db.php';

$password = password_hash("admin123", PASSWORD_DEFAULT);

try {
    $stmt = $db->prepare("INSERT INTO users (username, password, role, approved)
                          VALUES (?, ?, 'admin', 1)");
    $stmt->execute(['admin', $password]);

    echo "Admin created!";
} catch (PDOException $e) {
    echo "Admin already exists.";
}
?>