<?php
session_start();
include 'db.php';

// current logged-in user
$currentUserId = $_SESSION['user']['id'] ?? 0;

$result = $db->query("
    SELECT messages.*, users.username 
    FROM messages 
    JOIN users ON users.id = messages.user_id 
    ORDER BY messages.id ASC
");

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

    $isOwn = $row['user_id'] == $currentUserId;

    $class = $isOwn ? 'message right' : 'message left';

    echo "<div class='$class'>
            <div class='bubble'>
                <span class='username'>" . htmlspecialchars($row['username']) . "</span><br>
                " . htmlspecialchars($row['message']) . "
            </div>
          </div>";
}
?>