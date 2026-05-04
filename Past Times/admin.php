<?php
session_start();
include 'connect.php';

// 🔒 Protect admin page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

$message = "";

// =====================
// HANDLE ACTIONS
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // APPROVE
    if (isset($_POST['approve'])) {
        $id = $_POST['id'];
        $conn->query("UPDATE users SET is_approved=1 WHERE id=$id");
        $message = "User approved";
    }

    // REJECT
    if (isset($_POST['reject'])) {
        $id = $_POST['id'];
        $conn->query("UPDATE users SET is_approved=2 WHERE id=$id");
        $message = "User rejected";
    }

    // DELETE
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $conn->query("DELETE FROM users WHERE id=$id");
        $message = "User deleted";
    }

    // UPDATE
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];

        $conn->query("UPDATE users SET name='$name', email='$email' WHERE id=$id");
        $message = "User updated";
    }
}
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
    max-width: 900px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
}

h2 {
    margin-bottom: 20px;
}

.user-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f9fafb;
    padding: 12px;
    margin-bottom: 10px;
    border-radius: 6px;
}

input[type="text"], input[type="email"] {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    padding: 6px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.approve { background: #22c55e; color:white; }
.reject { background: #f59e0b; color:white; }
.delete { background: #ef4444; color:white; }
.update { background: #3b82f6; color:white; }

.pending { background: #fef3c7; padding:3px 8px; border-radius:5px; }
.approved { background: #d1fae5; padding:3px 8px; border-radius:5px; }
.rejected { background: #fee2e2; padding:3px 8px; border-radius:5px; }

.message {
    padding: 10px;
    margin-bottom: 15px;
    background: #d1fae5;
    border-radius: 5px;
}

.home-btn {
    display: inline-block;
    padding: 10px 20px;
    background: #2563eb;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}
</style>

</head>

<body>

<div class="container">

<h2>Admin Dashboard</h2>

<?php if ($message): ?>
<div class="message"><?php echo $message; ?></div>
<?php endif; ?>

<?php
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");

while($row = $result->fetch_assoc()):
?>

<div class="user-row">

<form method="POST">

    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <input type="text" name="name" value="<?php echo $row['name']; ?>">
    <input type="email" name="email" value="<?php echo $row['email']; ?>">

    <!-- STATUS DISPLAY -->
    <?php if ($row['is_approved'] == 0): ?>
        <span class="pending">PENDING</span>
    <?php elseif ($row['is_approved'] == 1): ?>
        <span class="approved">APPROVED</span>
    <?php else: ?>
        <span class="rejected">REJECTED</span>
    <?php endif; ?>

    <div>

        <button name="update" class="update">Save</button>

        <?php if ($row['is_approved'] == 0): ?>
            <button name="approve" class="approve">Approve</button>
            <button name="reject" class="reject">Reject</button>
        <?php endif; ?>

        <button name="delete" class="delete" onclick="return confirm('Delete user?')">
            Delete
        </button>

    </div>

</form>

</div>

<?php endwhile; ?>

</div>

<div style="text-align:center; margin-top:20px;">
    <a href="home.php" class="home-btn">Logout</a>
</div>

</body>
</html>