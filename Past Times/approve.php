<?php
include "connect.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: ID not provided");
}

$id = intval($_GET['id']); // safer

$sql = "UPDATE users SET status='approved' WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo "User approved successfully";
} else {
    echo "Error updating user: " . mysqli_error($conn);
}
?>