<?php
include 'connect.php';

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        $message = "Fields cannot be empty";
        $messageType = "error";
    }
    elseif ($password != $confirm) {
        $message = "Passwords do not match";
        $messageType = "error";
    }
    elseif (!isset($_POST['terms'])) {
        $message = "You must accept the Terms & Conditions";
        $messageType = "error";
    }
    else {

        $hashedPassword = md5($password);

        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

        if (mysqli_num_rows($check) > 0) {
            $message = "Email already exists";
            $messageType = "error";
        } else {

            $sql = "INSERT INTO users (name,email,password,is_approved,role)
                    VALUES ('$name','$email','$hashedPassword',0,'user')";

            if (mysqli_query($conn, $sql)) {
                $message = "Account created! Waiting for admin approval.";
                $messageType = "success";
            } else {
                $message = "Error: " . mysqli_error($conn);
                $messageType = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sign Up</title>

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

.signup-container {
    background: #ffffff;
    padding: 40px;
    width: 400px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    text-align: center;
}

.signup-container h2 {
    margin-bottom: 20px;
    font-size: 28px;
    color: #333;
}

.signup-container form {
    display: flex;
    flex-direction: column;
}

.signup-container input {
    padding: 12px 15px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
}

.signup-container input:focus {
    border-color: #4a90e2;
    outline: none;
}

.signup-container button {
    padding: 12px;
    background-color: #4a90e2;
    color: #fff;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.signup-container button:hover {
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

.terms {
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 15px;
    text-align: left;
}

.terms a {
    color: #4a90e2;
    text-decoration: none;
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

<div class="signup-container">

<h2>Create Account</h2>

<?php if($message != ""): ?>
<div class="message <?php echo $messageType; ?>">
    <?php echo $message; ?>
</div>
<?php endif; ?>

<form method="POST">

    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>

    <label class="terms">
        <input type="checkbox" name="terms" required>
        I agree to the <a href="#">Terms & Conditions</a>
    </label>

    <button type="submit">Sign Up</button>

</form>

<div class="footer">
    Already have an account? <a href="login.php">Login</a>
</div>

</div>

</body>
</html>