<?php
include 'connect.php';

$message = "";

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $conn->query("DELETE FROM users WHERE id=$id");

    $message = "User deleted successfully.";

} else {
    $message = "No user ID provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete User</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 350px;
        }

        .message {
            font-size: 18px;
            margin-bottom: 20px;
            color: #111827;
        }

        .success {
            color: #16a34a;
        }

        .error {
            color: #dc2626;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.2s;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .danger-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="box">

    <div class="danger-icon">🗑️</div>

    <div class="message <?php echo ($message === "User deleted successfully.") ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
    </div>

    <a class="btn" href="admin.php">Back to Admin</a>

</div>

</body>
</html>