<?php
session_start();
include 'db.php';

// check login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];

// ✅ DELETE ALL MESSAGES (ADMIN ONLY)
if (isset($_POST['delete_all']) && $user['role'] === 'admin') {
    $db->exec("DELETE FROM messages");
    $db->exec("DELETE FROM sqlite_sequence WHERE name='messages'");
    header("Location: chat.php");
    exit;
}

// ✅ SEND MESSAGE (NO DUPLICATE ON REFRESH)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_all'])) {

    $msg = trim($_POST['message']);

    if (!empty($msg)) {
        $stmt = $db->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
        $stmt->execute([$user['id'], $msg]);
    }

    header("Location: chat.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Group Chat</title>
    <style>
        body {
            font-family: Arial;
            max-width: 600px;
            margin: auto;
        }

        h2 {
            text-align: center;
        }

        .chat-box {
            border: 1px solid #ccc;
            height: 400px;
            overflow-y: auto;
            padding: 10px;
            background: #f0f0f0;
            margin-bottom: 10px;
        }

        /* message layout */
        .message {
            display: flex;
            margin: 5px 0;
        }

        .left {
            justify-content: flex-start;
        }

        .right {
            justify-content: flex-end;
        }

        /* message bubble */
        .bubble {
            max-width: 70%;
            padding: 10px;
            border-radius: 10px;
            background: #ddd;
        }

        /* own message */
        .right .bubble {
            background: #4CAF50;
            color: white;
        }

        .username {
            font-size: 12px;
            font-weight: bold;
        }

        form {
            display: flex;
            gap: 5px;
        }

        input {
            flex: 1;
            padding: 10px;
        }

        button {
            padding: 10px;
            cursor: pointer;
        }

        .delete-btn {
            background: red;
            color: white;
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>


<script>
history.pushState(null, null, location.href);
window.onpopstate = function () {
    history.go(1);
};
</script>

<body>

<h2>💬 Group Chat</h2>

<!-- ADMIN DELETE BUTTON -->
<?php if ($user['role'] === 'admin'): ?>
<form method="POST" onsubmit="return confirm('Delete all messages?');">
    <button type="submit" name="delete_all" class="delete-btn">
        🗑 Delete All Messages
    </button>
</form>
<?php endif; ?>

<!-- CHAT BOX -->
<div class="chat-box" id="chatBox"></div>

<!-- SEND MESSAGE -->
<form method="POST">
    <input name="message" required placeholder="Type message..." autocomplete="off">
    <button type="submit">Send</button>
</form>

<br>
<a href="dashboard.php">⬅ Back</a>

<script>
// load messages
function loadMessages() {
    fetch('fetch_messages.php')
        .then(response => response.text())
        .then(data => {
            let chatBox = document.getElementById("chatBox");

            let isNearBottom = chatBox.scrollHeight - chatBox.scrollTop <= chatBox.clientHeight + 50;

            chatBox.innerHTML = data;

            if (isNearBottom) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
}

// auto refresh every 2 seconds
setInterval(loadMessages, 2000);

// first load
loadMessages();
</script>

</body>
</html>