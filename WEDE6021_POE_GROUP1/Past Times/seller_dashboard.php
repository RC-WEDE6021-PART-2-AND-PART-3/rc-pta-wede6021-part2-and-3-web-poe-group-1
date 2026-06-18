<?php
session_start();
include 'connect.php';

/* =====================
   LOGIN CHECK
===================== */
$seller_id = $_SESSION['user_id'] ?? 2;
$admin_id = 1;

/* =====================
   SEND MESSAGE
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {

    $receiver_id = (int)$_POST['receiver_id'];
    $message = trim($_POST['message']);

    if (!empty($message)) {

        $stmt = $conn->prepare("
            INSERT INTO messages (sender_id, receiver_id, message)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("iis", $seller_id, $receiver_id, $message);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: seller.php?chat=$receiver_id");
    exit();
}

/* =====================
   ACTIVE CHAT
===================== */
$chat_user = isset($_GET['chat']) ? (int)$_GET['chat'] : $admin_id;

/* =====================
   GET MESSAGES
===================== */
$messages = $conn->query("
    SELECT * FROM messages
    WHERE (sender_id = $seller_id AND receiver_id = $chat_user)
       OR (sender_id = $chat_user AND receiver_id = $seller_id)
    ORDER BY created_at ASC
");

/* =====================
   STATS (UNCHANGED)
===================== */
$totalProducts = 125;
$totalOrders = 342;
$totalRevenue = 15890;
$pendingOrders = 18;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Seller Dashboard</title>

<style>
/* KEEP YOUR ORIGINAL DESIGN */
* { margin:0; padding:0; box-sizing:border-box; font-family:-apple-system, BlinkMacSystemFont,'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;}
body {background:#f9fafb; color:#1f2937; line-height:1.6;}

header {background:white; border-bottom:1px solid #e5e7eb; position:sticky; top:0; z-index:100;}
.header-container {max-width:1280px; margin:0 auto; padding:1rem 1.5rem; display:flex; justify-content:space-between; align-items:center;}
.logo {height:50px; cursor:pointer;}
.header-actions {display:flex; gap:1rem; align-items:center;}
.btn-primary {background:#2563eb; color:white; border:none; padding:.5rem 1rem; border-radius:4px; cursor:pointer;}
.btn-secondary {background:white; color:#2563eb; border:1px solid #2563eb; padding:.5rem 1rem; border-radius:4px; cursor:pointer;}

.cards {display:grid; grid-template-columns:repeat(auto-fit, minmax(200px,1fr)); gap:1.5rem; margin:2rem 0;}
.card {background:white; padding:1.5rem; border-radius:6px; box-shadow:0 3px 10px rgba(0,0,0,0.05);}
.card h3 {font-size:1rem; color:#4b5563; margin-bottom:.5rem;}
.card p {font-size:1.5rem; font-weight:600; color:#1f2937;}

.table-section {background:white; border-radius:6px; box-shadow:0 3px 10px rgba(0,0,0,0.05); padding:1.5rem; margin-bottom:2rem;}
.messages-section {background:white; border-radius:6px; box-shadow:0 3px 10px rgba(0,0,0,0.05); padding:1.5rem; margin-bottom:2rem;}

/* CHAT */
.chat-box {
    display:flex;
    flex-direction:column;
    border:1px solid #e5e7eb;
    border-radius:6px;
    height:400px;
    overflow:hidden;
}

.chat-messages {
    flex:1;
    padding:10px;
    overflow-y:auto;
    background:#f3f4f6;
}

.msg {
    padding:8px;
    margin:6px 0;
    border-radius:6px;
    max-width:70%;
}

.sent {
    background:#2563eb;
    color:white;
    margin-left:auto;
}

.received {
    background:#e5e7eb;
}

.chat-input {
    display:flex;
    border-top:1px solid #e5e7eb;
}

.chat-input input {
    flex:1;
    padding:10px;
    border:none;
    outline:none;
}

.chat-input button {
    background:#2563eb;
    color:white;
    border:none;
    padding:10px 15px;
}
</style>
</head>

<body>

<header>
<div class="header-container">
    <img src="logo.jpeg" class="logo" onclick="location.reload()">
    <div class="header-actions">
        <button class="btn-primary" onclick="location.href='seller.php'">Seller Request</button>
        <button class="btn-secondary" onclick="location.href='home.php'">Logout</button>
    </div>
</div>
</header>

<div class="container">

<!-- STATS -->
<div class="cards">
    <div class="card"><h3>Total Products</h3><p><?php echo $totalProducts; ?></p></div>
    <div class="card"><h3>Total Orders</h3><p><?php echo $totalOrders; ?></p></div>
    <div class="card"><h3>Total Revenue</h3><p>R<?php echo number_format($totalRevenue,2); ?></p></div>
    <div class="card"><h3>Pending Orders</h3><p><?php echo $pendingOrders; ?></p></div>
</div>

<!-- INBOX -->
<section class="messages-section">
<h2>Inbox</h2>

<!-- CHAT BOX -->
<div class="chat-box">

<!-- MESSAGES -->
<div class="chat-messages">

<?php while($m = $messages->fetch_assoc()): ?>

<div class="msg <?php echo ($m['sender_id'] == $seller_id) ? 'sent' : 'received'; ?>">
    <?php echo htmlspecialchars($m['message']); ?>
    <br>
    <small><?php echo $m['created_at']; ?></small>
</div>

<?php endwhile; ?>

</div>

<!-- SEND MESSAGE -->
<form method="POST" class="chat-input">
    <input type="hidden" name="receiver_id" value="<?php echo $chat_user; ?>">
    <input type="text" name="message" placeholder="Type message..." required>
    <button type="submit" name="send_message">Send</button>
</form>

</div>

</section>

</div>

</body>
</html>