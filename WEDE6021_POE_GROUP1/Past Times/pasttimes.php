<?php
$conn = new mysqli("localhost", "root", "");

$conn->query("CREATE DATABASE clothingstore");
$conn->query("USE clothingstore");

$conn->query("CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100),
email VARCHAR(100),
password VARCHAR(100)
)");

echo "Setup complete";
?>