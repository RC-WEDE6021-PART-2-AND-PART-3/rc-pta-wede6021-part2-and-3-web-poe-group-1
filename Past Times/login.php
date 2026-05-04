<?php
session_start();
include 'connect.php';

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'] ?? "";
    $password = $_POST['password'] ?? "";

    if (empty($email) || empty($password)) {
        $message = "Fields cannot be empty";
        $messageType = "error";
    } else {

        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // ✅ Check password
            if ($user['password'] == $password) {

                // 🚫 CHECK STATUS FIRST
                if ($user['status'] == 'pending') {
                    $message = "Your account is waiting for admin approval";
                    $messageType = "error";

                } elseif ($user['status'] == 'rejected') {
                    $message = "Your account was rejected by admin";
                    $messageType = "error";

                } else {

                    // ✅ Save session
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];

                    // 🔥 ADMIN / USER REDIRECT
                    if ($user['role'] == 'admin') {
                        header("Location: admin.php");
                        exit();
                    } else {
                        header("Location: home.php");
                        exit();
                    }
                }

            } else {
                $message = "Wrong password";
                $messageType = "error";
            }

        } else {
            $message = "User not found";
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: #f0f2f7;
}

.login-container {
    background: #ffffff;
    padding: 40px;
    width: 400px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    text-align: center;
}

.login-container h2 {
    margin-bottom: 20px;
    font-size: 28px;
    color: #333;
}

.login-container form {
    display: flex;
    flex-direction: column;
}

.login-container input {
    padding: 12px 15px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
}

.login-container button {
    padding: 12px;
    background-color: #4a90e2;
    color: #fff;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.login-container button:hover {
    background-color: #357ABD;
}

.message {
    margin-bottom: 10px;
    font-size: 14px;
    padding: 10px;
    border-radius: 6px;
}

.message.error {
    background-color: #f8d7da;
    color: #721c24;
}

.message.success {
    background-color: #d4edda;
    color: #155724;
}

.footer {
    margin-top: 15px;
    font-size: 14px;
}

.footer a {
    color: #4a90e2;
    text-decoration: none;
}
</style>

</head>
<body>

<div class="login-container">
    <h2>Login</h2>

<?php if(!empty($message)): ?>
    <div class="message <?php echo $messageType; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>

    <button type="submit">Log in</button>
</form>

<div class="footer">
    <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
</div>

</div>

</body>
</html>