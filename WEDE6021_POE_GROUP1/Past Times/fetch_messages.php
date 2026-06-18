<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit("Access denied");
}

$admin_id = $_SESSION['user_id'] ?? 1;
$user_id = isset($_GET['user']) ? (int)$_GET['user'] : 0;

if ($user_id <= 0) {
    exit();
}

$chat = $conn->query("
    SELECT * FROM messages
    WHERE (sender_id=$admin_id AND receiver_id=$user_id)
       OR (sender_id=$user_id AND receiver_id=$admin_id)
    ORDER BY id ASC
");

while ($m = $chat->fetch_assoc()) {

    $class = ($m['sender_id'] == $admin_id) ? "sent" : "received";

    echo "<div class='msg $class'>"
        . htmlspecialchars($m['message']) .
    "</div>";
}
?>