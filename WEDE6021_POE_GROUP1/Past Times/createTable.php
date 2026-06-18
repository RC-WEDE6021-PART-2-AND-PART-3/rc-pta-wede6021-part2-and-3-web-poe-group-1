<?php
include 'connect.php';

// USERS TABLE
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    is_approved INT NOT NULL DEFAULT 0
)");

// CLOTHES REQUESTS TABLE (FIXED)
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS clothes_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    brand VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// delete old data
$conn->query("DELETE FROM users");

// insert new data
$conn->query("INSERT INTO users (name, email, password) VALUES
('John','john@mail.com','1234'),
('Mary','mary@mail.com','1234'),
('Sam','sam@mail.com','1234'),
('Lisa','lisa@mail.com','1234'),
('Tom','tom@mail.com','1234')
");

echo "Data reset done!";

// CREATE DEFAULT ADMIN
$admin_email = "admin@gmail.com";
$admin_password = password_hash("admin123", PASSWORD_DEFAULT);

$check_admin = mysqli_query($conn, "SELECT * FROM users WHERE email='$admin_email'");

if (mysqli_num_rows($check_admin) == 0) {
    mysqli_query($conn, "INSERT INTO users(name, email, password, role, is_approved)
    VALUES('Admin', '$admin_email', '$admin_password', 'admin', 1)");
}
?>