<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

$message = "";

/* =====================
   ADMIN ID
===================== */
$admin_id = $_SESSION['user_id'] ?? 1;

/* =====================
   HANDLE ACTIONS
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ---------- USERS ---------- */

    if (isset($_POST['approve'])) {
        $id = (int)$_POST['id'];
        $conn->query("UPDATE users SET is_approved=1 WHERE id=$id");
        $message = "User approved";
    }

    if (isset($_POST['reject'])) {
        $id = (int)$_POST['id'];
        $conn->query("UPDATE users SET is_approved=2 WHERE id=$id");
        $message = "User rejected";
    }

    if (isset($_POST['approve_seller'])) {
        $id = (int)$_POST['id'];
        $conn->query("UPDATE users SET is_approved=3 WHERE id=$id");
        $message = "Seller approved";
    }

    if (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        $conn->query("DELETE FROM users WHERE id=$id");
        $message = "User deleted";
    }

    if (isset($_POST['update'])) {
        $id = (int)$_POST['id'];
        $email = $conn->real_escape_string($_POST['email']);

        $conn->query("
            UPDATE users
            SET email='$email'
            WHERE id=$id
        ");

        $message = "User updated";
    }

    /* ---------- CLOTHING REQUESTS ---------- */

    if (isset($_POST['approve_request'])) {

        $id = (int)$_POST['request_id'];
        $result = $conn->query("SELECT * FROM clothes_requests WHERE id=$id");

        if ($result && $result->num_rows > 0) {

            $item = $result->fetch_assoc();

            $name = $conn->real_escape_string($item['item_name']);
            $price = 0;

            $conn->query("
                INSERT INTO items (name, price)
                VALUES ('$name', '$price')
            ");

            $conn->query("DELETE FROM clothes_requests WHERE id=$id");

            $message = "Request approved";
        }
    }

    if (isset($_POST['reject_request'])) {
        $id = (int)$_POST['request_id'];
        $conn->query("DELETE FROM clothes_requests WHERE id=$id");
        $message = "Request rejected";
    }

    if (isset($_POST['update_request'])) {
        $id = (int)$_POST['request_id'];

        $item_name = $conn->real_escape_string($_POST['item_name']);
        $brand = $conn->real_escape_string($_POST['brand']);
        $description = $conn->real_escape_string($_POST['description']);

        $conn->query("
            UPDATE clothes_requests
            SET item_name='$item_name',
                brand='$brand',
                description='$description'
            WHERE id=$id
        ");

        $message = "Request updated";
    }

    if (isset($_POST['delete_request'])) {
        $id = (int)$_POST['request_id'];
        $conn->query("DELETE FROM clothes_requests WHERE id=$id");
        $message = "Request deleted";
    }

    /* ---------- SEND MESSAGE ---------- */

    if (isset($_POST['send_chat'])) {

        $receiver_id = (int)$_POST['receiver_id'];
        $msg = $conn->real_escape_string($_POST['message']);

        $conn->query("
            INSERT INTO messages (sender_id, receiver_id, message)
            VALUES ($admin_id, $receiver_id, '$msg')
        ");

        header("Location: admin.php?chat=$receiver_id");
        exit();
    }
}

/* =====================
   CHAT USER SELECTED
===================== */
$chat_user = isset($_GET['chat']) ? (int)$_GET['chat'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<style>
body {
    font-family: Arial;
    background: #f3f4f6;
    padding: 40px;
}

.container {
    max-width: 1100px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
}

.user-row {
    background: #f9fafb;
    padding: 12px;
    margin-bottom: 10px;
    border-radius: 6px;
}

input, textarea {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin: 3px;
}

button {
    padding: 6px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.approve { background: #22c55e; color: white; }
.reject { background: #f59e0b; color: white; }
.delete { background: #ef4444; color: white; }
.update { background: #3b82f6; color: white; }

.message-box {
    background: #d1fae5;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
}

/* CHAT */
.chat-wrapper {
    display: flex;
    height: 500px;
    border: 1px solid #ddd;
    margin-top: 20px;
    border-radius: 10px;
    overflow: hidden;
}

.user-list {
    width: 30%;
    border-right: 1px solid #ddd;
    overflow-y: auto;
    background: #f9fafb;
}

.user-list a {
    display: block;
    padding: 10px;
    text-decoration: none;
    color: black;
    border-bottom: 1px solid #eee;
}

.user-list a:hover {
    background: #e5e7eb;
}

.chat-box {
    width: 70%;
    display: flex;
    flex-direction: column;
}

.messages {
    flex: 1;
    padding: 10px;
    overflow-y: auto;
    background: #f3f4f6;
}

.msg {
    padding: 8px;
    margin: 5px 0;
    border-radius: 6px;
    max-width: 70%;
}

.sent {
    background: #2563eb;
    color: white;
    margin-left: auto;
}

.received {
    background: #e5e7eb;
}

.chat-input {
    display: flex;
    border-top: 1px solid #ddd;
}

.chat-input input {
    flex: 1;
    padding: 10px;
    border: none;
}

.chat-input button {
    background: #2563eb;
    color: white;
}
</style>

</head>

<body>

<div class="container">

<h2>Admin Dashboard</h2>

<?php if ($message): ?>
<div class="message-box"><?php echo $message; ?></div>
<?php endif; ?>

<!-- USERS -->
<h3>Users</h3>

<?php
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");

while($u = $users->fetch_assoc()):
?>

<div class="user-row">

<form method="POST">

<input type="hidden" name="id" value="<?php echo $u['id']; ?>">

<input type="email" name="email" value="<?php echo $u['email']; ?>">

<button name="update" class="update">Save</button>
<button name="approve" class="approve">Approve</button>
<button name="approve_seller" class="approve">Seller</button>
<button name="reject" class="reject">Reject</button>
<button name="delete" class="delete">Delete</button>

</form>

</div>

<?php endwhile; ?>

<!-- CLOTHING REQUESTS -->
<hr>
<h3>Clothing Requests</h3>

<?php
$req = $conn->query("SELECT * FROM clothes_requests ORDER BY id DESC");

while($r = $req->fetch_assoc()):
?>

<div class="user-row">

<form method="POST">

<input type="hidden" name="request_id" value="<?php echo $r['id']; ?>">

<input type="text" name="item_name" value="<?php echo $r['item_name']; ?>">
<input type="text" name="brand" value="<?php echo $r['brand']; ?>">
<input type="text" name="description" value="<?php echo $r['description']; ?>">

<button name="update_request" class="update">Save</button>
<button name="approve_request" class="approve">Approve</button>
<button name="reject_request" class="reject">Reject</button>
<button name="delete_request" class="delete">Delete</button>

</form>

</div>

<?php endwhile; ?>

<!-- CHAT INBOX -->
<hr>
<h3>Inbox</h3>

<div class="chat-wrapper">

<!-- USERS LIST -->
<div class="user-list">

<?php
$allUsers = $conn->query("SELECT * FROM users ORDER BY id DESC");
while($u = $allUsers->fetch_assoc()):
?>
<a href="admin.php?chat=<?php echo $u['id']; ?>">
<?php echo $u['email']; ?>
</a>
<?php endwhile; ?>

</div>

<!-- CHAT BOX -->
<div class="chat-box">

<div class="messages">

<?php
if ($chat_user > 0):

$chat = $conn->query("
SELECT * FROM messages
WHERE (sender_id=$admin_id AND receiver_id=$chat_user)
OR (sender_id=$chat_user AND receiver_id=$admin_id)
ORDER BY created_at ASC
");

while($m = $chat->fetch_assoc()):

$class = ($m['sender_id'] == $admin_id) ? "sent" : "received";
?>

<div class="msg <?php echo $class; ?>">
<?php echo htmlspecialchars($m['message']); ?>
</div>

<?php endwhile; endif; ?>

</div>

<?php if ($chat_user > 0): ?>
<form method="POST" class="chat-input">
<input type="hidden" name="receiver_id" value="<?php echo $chat_user; ?>">
<input type="text" name="message" placeholder="Type message..." required>
<button name="send_chat">Send</button>
</form>
<?php endif; ?>

</div>

</div>

</div>

</body>
</html>